<?php
session_start();
include ('connect.php');

// Kira notification belum dibaca
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM notification WHERE is_read = 0");
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$totalNotif = $data['total'];
$stmt->close();

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dentist Dashboard - ToothEase</title>
  <link rel="stylesheet" href="homepagedentist.css">
</head>
<body>

<div class="header">
  <h1>Welcome, Dr. Norazah</h1>
</div>

<div class="container">
  <h2>Dentist Dashboard</h2>

  <div class="menu">

    <a href="approveappointment.php">Approve / Reject Appointment</a>
    <a href="treatmentrecord.php">View Treatment Record</a>
    <a href="treatment.php">Treatment Record</a>
    <a href="viewfeedback.php">View Feedback</a>
    <a href="record.php">System Record</a> 
    <a href="viewnotification.php">Notifications (<?= $totalNotif ?>)</a>

  </div>

  <div class="logout">
    <p><a href="logout.php">Logout</a></p>
  </div>
</div>

</body>
</html>