<?php
session_start();
include('connect.php');

$ic_no = "";
$student_staff_no = "";

// Terima data dari POST (search) atau GET (redirect dari cancel)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ic_no = $_POST['ic_no'];
    $student_staff_no = $_POST['student_staff_no'];
} elseif (isset($_GET['ic_no']) && isset($_GET['student_staff_no'])) {
    $ic_no = $_GET['ic_no'];
    $student_staff_no = $_GET['student_staff_no'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Appointment List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        form input, form button {
            padding: 10px;
            margin: 5px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        .cancel-btn {
            background: #e74c3c;
            color: white;
            padding: 6px 12px;
            border: none;
            cursor: pointer;
        }
        .success-msg {
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>ToothEase</h1>
    <div class="nav-links">
      <a href="home.php">Home</a>
      <a href="appointment.php">Book Appointment</a>
      <a href="listappointment.php">List Appointment</a>
      <a href="logout.php">Logout</a>
    </div>

<h2>List Appointment</h2>

<?php
if (isset($_GET['msg']) && $_GET['msg'] == 'cancel_success') {
    echo "<p class='success-msg'> Appointment cancelled successfully.</p>";
}
?>

<form method="POST" action="listappointment.php" id="searchForm">
    <input type="text" name="ic_no" placeholder="Enter IC No" required value="<?php echo htmlspecialchars($ic_no); ?>">
    <input type="text" name="student_staff_no" placeholder="Enter Matric/Staff No" required value="<?php echo htmlspecialchars($student_staff_no); ?>">
    <button type="submit">Search</button>
</form>

<?php

if ($ic_no != "" && $student_staff_no != "") {
    // Cari user berdasarkan ic_no & student_staff_no
    $stmt = $conn->prepare("SELECT * FROM user WHERE ic_no = ? AND student_staff_no = ?");
    $stmt->bind_param("ss", $ic_no, $student_staff_no);
    $stmt->execute();
    $resultUser = $stmt->get_result();

    if ($resultUser->num_rows > 0) {
        $user = $resultUser->fetch_assoc();
        $user_id = $user['id'];

        // Dapatkan janji temu user
        $stmt2 = $conn->prepare("SELECT * FROM appointment WHERE user_id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $resultApp = $stmt2->get_result();

        if ($resultApp->num_rows > 0) {
            echo "<h3>Appointments for " . htmlspecialchars($user['name']) . "</h3>";
            echo "<table>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Name</th>
                        <th>IC</th>
                        <th>Matric No.</th>
                        <th>Action</th>
                    </tr>";

            while ($row = $resultApp->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['dateApp']) . "</td>
                        <td>" . htmlspecialchars($row['timeApp']) . "</td>
                        <td>" . htmlspecialchars($user['name']) . "</td>
                        <td>" . htmlspecialchars($user['ic_no']) . "</td>
                        <td>" . htmlspecialchars($user['student_staff_no']) . "</td>
                        <td>
                            <form method='POST' action='cancelappointment.php' onsubmit='return confirm(\"Are you sure you want to cancel this appointment?\");'>
                                <input type='hidden' name='idApp' value='" . $row['idApp'] . "'>
                                <input type='hidden' name='ic_no' value='" . htmlspecialchars($ic_no) . "'>
                                <input type='hidden' name='student_staff_no' value='" . htmlspecialchars($student_staff_no) . "'>
                                <button class='cancel-btn' type='submit'>Cancel Appointment</button>
                            </form>
                        </td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No appointments found for this user.</p>";
        }

        $stmt2->close();
    } else {
        echo "<p>User not found.</p>";
    }

    $stmt->close();
}
$conn->close();
?>


</body>
</html>
