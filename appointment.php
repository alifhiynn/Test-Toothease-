<?php
session_start();
include('connect.php');

// Pastikan user telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil maklumat user login
$userData = null;
$stmt = $conn->prepare("SELECT id, name, ic_no, faculty_ptj, gender, category FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();

// Senarai tarikh penuh (lebih dari 7 janji temu sehari)
$bookedDates = [];
$sql = "SELECT dateApp, COUNT(*) as count FROM appointment GROUP BY dateApp HAVING count >= 7";
$resultDates = $conn->query($sql);
if ($resultDates) {
    while ($row = $resultDates->fetch_assoc()) {
        $bookedDates[] = $row['dateApp'];
    }
}

$appointmentSuccess = false;
$errorMsg = "";

// Simpan appointment
if (isset($_POST['book_appointment'])) {
    $dateApp = $_POST['dateApp'] ?? '';
    $timeApp = $_POST['timeApp'] ?? '';

    if ($dateApp && $timeApp) {
        // Semak slot sama ada penuh
        $stmtCheck = $conn->prepare("SELECT COUNT(*) as count FROM appointment WHERE dateApp = ? AND timeApp = ?");
        $stmtCheck->bind_param("ss", $dateApp, $timeApp);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $rowCheck = $resultCheck->fetch_assoc();
        $stmtCheck->close();

        if ($rowCheck['count'] > 0) {
            $errorMsg = "Slot tarikh & masa telah penuh. Sila pilih yang lain.";
        } else {
            // Simpan janji temu
            $stmt = $conn->prepare("INSERT INTO appointment (id, dateApp, timeApp) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $dateApp, $timeApp);
            if ($stmt->execute()) {
                $_SESSION['appointment_data'] = [
                    'name' => $userData['name'],
                    'ic_no' => $userData['ic_no'],
                    'faculty_ptj' => $userData['faculty_ptj'],
                    'gender' => $userData['gender'],
                    'category' => $userData['category'],
                    'dateApp' => $dateApp,
                    'timeApp' => $timeApp
                ];

                header("Location: successappointment.php");
                exit();
            } else {
                $errorMsg = "Gagal simpan janji temu: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Book Appointment - ToothEase</title>
  <link rel="stylesheet" href="appointment.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
  <h2>Book Appointment</h2>

  <div class="user-info">
    <p><strong>Name:</strong> <?=htmlspecialchars($userData['name'])?></p>
    <p><strong>IC Number:</strong> <?=htmlspecialchars($userData['ic_no'])?></p>
    <p><strong>Faculty/PTJ:</strong> <?=htmlspecialchars($userData['faculty_ptj'])?></p>
    <p><strong>Gender:</strong> <?=htmlspecialchars($userData['gender'])?></p>
    <p><strong>Patient Category:</strong> <?=htmlspecialchars($userData['category'])?></p>
  </div>

  <?php if ($errorMsg): ?>
    <p class="error"><?=htmlspecialchars($errorMsg)?></p>
  <?php endif; ?>

  <form method="POST" action="" id="appointmentForm">
    <input type="hidden" name="id" value="<?= $user_id ?>" />

    <label for="dateApp">Select Date:</label>
    <input type="date" name="dateApp" id="dateApp" required min="<?=date('Y-m-d')?>" />

    <label>Select Time:</label>
    <div id="timeButtons">
      <?php
        $availableTimes = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
        foreach ($availableTimes as $time) {
            echo '<button type="button" class="time-btn" data-time="' . $time . '">' . $time . '</button>';
        }
      ?>
    </div>

    <input type="hidden" name="timeApp" id="timeApp" required />

    <br />
    <button type="submit" name="book_appointment" style="margin-top:15px; padding:10px 20px;">Book Appointment</button>
  </form>
</div>

<script>
  const bookedDates = <?= json_encode($bookedDates) ?>;
  const dateInput = document.getElementById('dateApp');
  const timeButtons = document.querySelectorAll('.time-btn');
  const timeInput = document.getElementById('timeApp');

  dateInput.addEventListener('change', () => {
    if (bookedDates.includes(dateInput.value)) {
      alert("Tarikh ini sudah penuh. Sila pilih tarikh lain.");
      dateInput.value = "";
    }
  });

  timeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      timeButtons.forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
      timeInput.value = btn.getAttribute('data-time');
    });
  });

  document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    if (!timeInput.value) {
      alert("Sila pilih masa appointment.");
      e.preventDefault();
    }
  });
</script>

</body>
</html>
