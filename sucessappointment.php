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
  <style>
    body { 
      font-family: Arial, sans-serif; background:#ecf0f1; padding: 20px;
     }
    .container { 
      max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.15);
     }
    .success { 
      color: green; font-size: 18px;
     }
    p { 
      margin: 8px 0;
     }
  </style>
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
  </div>
</div>

</body>
</html>
