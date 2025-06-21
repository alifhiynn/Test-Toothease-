<?php
session_start();
include ('connect.php');

// // Semak jika bukan dentist login 
// if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'azah') {
//     header("Location: findlogin.php");
//     exit();
// }
// ?>

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
<<<<<<< HEAD
    <a href="approveappointment.php">Approve / Reject Appointment</a>
    <a href="treatment_record.php">Treatment Record</a>
=======
    <a href="approve_appointment.php">Approve / Reject Appointment</a>
    <a href="treatment.php">Treatment Record</a>
>>>>>>> 1c2e7b7674fd1794bbcdb554909b7cd0b5c719be
    <a href="treatment_record.php">Treatment History</a>
  </div>

  <div class="logout">
    <p><a href="logout.php">Logout</a></p>
  </div>
</div>

</body>
</html>