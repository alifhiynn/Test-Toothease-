<?php
session_start();
include('connect.php');


// Kira notification belum dibaca
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM notification WHERE is_read = 0");
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$totalNotif = $data['total'];
$stmt->close();

 
// Dapatkan hari ini
$today = date('Y-m-d');

// NOTIS: Jumlah janji temu yang dibatalkan hari ini
$stmt = $conn->prepare("
    SELECT COUNT(*) AS cancelled_today 
    FROM appointment 
    WHERE status = 'CANCELLED' AND DATE(dateApp) = CURDATE()
");
$stmt->execute();
$cancelled_result = $stmt->get_result()->fetch_assoc();
$cancelledCount = $cancelled_result['cancelled_today'] ?? 0;
$stmt->close();
//  Notis - appointment yang lepas tapi rawatan belum direkod
$stmt2 = $conn->prepare("
    SELECT COUNT(*) AS pending_treatment 
    FROM appointment a 
    WHERE a.status = 'APPROVED' AND a.dateApp < ? 
    AND NOT EXISTS (
        SELECT * FROM treatment_record t 
        WHERE t.appointment_id = a.idApp
    )
");
$stmt2->bind_param("s", $today);
$stmt2->execute();
$pending_result = $stmt2->get_result()->fetch_assoc();
$pendingTreatment = $pending_result['pending_treatment'] ?? 0;
$stmt2->close();

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

  <!-- NOTIS SECTION -->
  <div class="notice-box">
    <?php if ($cancelledCount > 0): ?>
      <div class="notice warning">⚠ <?= $cancelledCount ?> pesakit telah <strong>batalkan janji temu hari ini</strong>.</div>
    <?php endif; ?>

    <?php if ($pendingTreatment > 0): ?>
      <div class="notice danger">❗ <?= $pendingTreatment ?> janji temu <strong>lepas belum direkod rawatan</strong>.</div>
    <?php endif; ?>

    <?php if ($cancelledCount == 0 && $pendingTreatment == 0): ?>
      <div class="notice success">✅ Tiada notis baharu untuk hari ini.</div>
    <?php endif; ?>
  </div>

  <!-- MENU -->
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
