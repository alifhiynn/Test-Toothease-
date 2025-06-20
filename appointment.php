<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testoothease";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variabel untuk simpan data user yang dicari
$userData = null;
$appointmentSuccess = false;
$errorMsg = "";

// Step 1 & 2: Cari user berdasarkan IC dan no staff/no matriks
if (isset($_POST['search_user'])) {
    $ic_no = $_POST['ic_no'] ?? '';
    $staff_student_no = $_POST['staff_student_no'] ?? '';

    // Query untuk dapatkan maklumat user - sesuaikan dengan table dan column user kamu
    $stmt = $conn->prepare("SELECT id, name, ic_no, faculty_ptj, gender, category FROM user WHERE ic_no = ? AND student_staff_no = ?");
    $stmt->bind_param("ss", $ic_no, $staff_student_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();

    if (!$userData) {
        $errorMsg = "User tidak ditemui. Sila semak IC dan No Staff/Matric anda.";
    }
}

// Step 3 & 4 & 5: Bila user pilih tarikh dan masa, simpan appointment
if (isset($_POST['book_appointment'])) {
    $user_id = $_POST['user_id'];
    $dateApp = $_POST['dateApp'] ?? '';
    $timeApp = $_POST['timeApp'] ?? '';

    if ($dateApp && $timeApp) {
        // Simpan appointment ke database
        $stmt = $conn->prepare("INSERT INTO appointment (user_id, dateApp, timeApp) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $dateApp, $timeApp);
        if ($stmt->execute()) {
    // Simpan data dalam session untuk dipaparkan
    $_SESSION['appointment_data'] = [
        'name' => $userData['name'],
        'ic_no' => $userData['ic_no'],
        'faculty_ptj' => $userData['faculty_ptj'],
        'gender' => $userData['gender'],
        'category' => $userData['category'],
        'dateApp' => $dateApp,
        'timeApp' => $timeApp
    ];

    // Redirect ke paparan success
    header("Location: sucessappointment.php");
    exit();
}

        } else {
            $errorMsg = "Fail to save an Appointment " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errorMsg = "Please Choose an Appointment Date.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Book Appointment - ToothEase</title>
  <style>
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f4f8fb;
  padding: 40px 15px;
  color: #333;
}

.container {
  max-width: 700px;
  margin: auto;
  background: white;
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

h2 {
  color: #00695c;
  margin-bottom: 25px;
  font-size: 2rem;
  font-weight: 700;
  text-align: center;
}

label {
  display: block;
  margin: 20px 0 6px;
  font-weight: 600;
  color: #004d40;
}

input[type="text"],
input[type="date"] {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  margin-bottom: 10px;
  transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="date"]:focus {
  border-color: #00695c;
  outline: none;
}

button,
input[type="submit"] {
  background-color: #00695c;
  color: white;
  padding: 12px 25px;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

button:hover,
input[type="submit"]:hover {
  background-color: #004d40;
  box-shadow: 0 5px 12px rgba(0, 105, 92, 0.3);
}

.user-info {
  background: #e0f2f1;
  padding: 20px;
  border-radius: 10px;
  margin-top: 20px;
  line-height: 1.6;
}

.user-info p {
  margin: 6px 0;
  font-size: 1rem;
}

.success {
  color: green;
  font-weight: 600;
  margin-top: 20px;
}

.error {
  color: red;
  margin-top: 15px;
  font-weight: 600;
}

/* Time Button */
#timeButtons {
  margin-top: 10px;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

button.time-btn {
  background-color: #80cbc4;
  color: #004d40;
  border: none;
  padding: 10px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

button.time-btn.selected {
  background-color: #00695c;
  color: white;
}

button.time-btn:hover {
  background-color: #4db6ac;
}

/* Responsive */
@media (max-width: 600px) {
  .container {
    padding: 20px;
  }

  #timeButtons {
    flex-direction: column;
  }

  button.time-btn {
    width: 100%;
  }
}

  </style>
</head>
<body>

<div class="container">
  <h2>Book Appointment</h2>

  <!-- Step 1: Form cari user -->
  <?php if (!$userData): ?>
    <form method="POST" action="">
      <label for="ic_no">IC Number:</label>
      <input type="text" name="ic_no" id="ic_no" required />

      <label for="matric_no">No Staff / No Matrik Student:</label>
      <input type="text" name="staff_student_no" id="staff_student_no" required />

      <button type="submit" name="search_user" style="margin-top:15px; padding:10px 20px;">Search</button>
    </form>

    <?php if ($errorMsg): ?>
      <p class="error"><?=htmlspecialchars($errorMsg)?></p>
    <?php endif; ?>

  <?php else: ?>
  
    <!-- Step 2: Papar maklumat user -->
    <div class="user-info">
      <p><strong>Name:</strong> <?=htmlspecialchars($userData['name'])?></p>
      <p><strong>IC Number:</strong> <?=htmlspecialchars($userData['ic_no'])?></p>
      <p><strong>Faculty/PTJ:</strong> <?=htmlspecialchars($userData['faculty_ptj'])?></p>
      <p><strong>Gender:</strong> <?=htmlspecialchars($userData['gender'])?></p>
      <p><strong>Patient Category:</strong> <?=htmlspecialchars($userData['category'])?></p>
    </div>

    <?php if ($appointmentSuccess): ?>
      <p class="success">Appointment berjaya ditempah!</p>
    <?php else: ?>

      <!-- Step 3: Form pilih tarikh dan masa -->
      <form method="POST" action="" id="appointmentForm">
      <input type="hidden" name="user_id" value="<?=htmlspecialchars($userData['id'])?>" />

        <label for="dateApp">Select Date:</label>
        <input type="date" name="dateApp" id="dateApp" required min="<?=date('Y-m-d')?>" />

        <label>Select Time:</label>
        <div id="timeButtons">
          <?php
            // Contoh waktu available, kamu boleh ambil dari database atau generate dinamik
            $availableTimes = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
            foreach ($availableTimes as $time) {
                echo '<button type="button" class="time-btn" data-time="' . $time . '">' . $time . '</button>';
            }
          ?>
        </div>

        <!-- input hidden untuk simpan masa yang dipilih -->
        <input type="hidden" name="timeApp" id="timeApp" required />

        <br />
        <button type="submit" name="book_appointment" style="margin-top:15px; padding:10px 20px;">Book Appointment</button>
      </form>

      <script>
        const timeButtons = document.querySelectorAll('.time-btn');
        const timeInput = document.getElementById('timeApp');

        timeButtons.forEach(btn => {
          btn.addEventListener('click', () => {
            // Clear semua selected button
            timeButtons.forEach(b => b.classList.remove('selected'));
            // Tandakan button yang dipilih
            btn.classList.add('selected');
            // Simpan masa yang dipilih ke hidden input
            timeInput.value = btn.getAttribute('data-time');
          });
        });

        // Validate form submit
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
          if (!timeInput.value) {
            alert("Sila pilih masa appointment.");
            e.preventDefault();
          }
        });
      </script>

    <?php endif; ?>

  <?php endif; ?>

</div>

</body>
</html>
