<?php
session_start();

if (!isset($_SESSION['appointment_data'])) {
    // Kalau tiada data dalam session, redirect balik
    header("Location: index.php");
    exit();
}

$data = $_SESSION['appointment_data'];
// Clear data dari session selepas digunakan
unset($_SESSION['appointment_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment Success - ToothEase</title>
  <link rel="stylesheet" href="successappointment.css">
</head>
<body>

<div class="container">
  <h2>Appointment is Successful!</h2>
  <div class="success">
    <p><strong>Nama:</strong> <?=htmlspecialchars($data['name'])?></p>
    <p><strong>IC Number:</strong> <?=htmlspecialchars($data['ic_no'])?></p>
    <p><strong>Faculty/PTJ:</strong> <?=htmlspecialchars($data['faculty_ptj'])?></p>
    <p><strong>Patient Category:</strong> <?=htmlspecialchars($data['category'])?></p>
    <p><strong>Date Appointment:</strong> <?=htmlspecialchars($data['dateApp'])?></p>
    <p><strong>Time Appointment:</strong> <?=htmlspecialchars($data['timeApp'])?></p>

    <br>
    <a href="home.php">
    <button>Back to Home</button>
    </a>
  </div>
</div>

</body>
</html>
