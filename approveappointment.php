<?php
session_start();
include('connect.php');

$selected_date = $_GET['date'] ?? date('Y-m-d'); // default hari ini
$selected_id = $_GET['id'] ?? null;
$statusMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $app_id = $_POST['idApp'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE appointment SET status = ? WHERE idApp = ?");
    $stmt->bind_param("si", $status, $app_id);// utk elak injection
    if ($stmt->execute()) {
        $statusMsg = "Status updated successfully!";
    }
    $stmt->close();
}

// Papar senarai janji temu pada tarikh dipilih
$stmt = $conn->prepare("
    SELECT a.idApp, a.dateApp, a.timeApp, a.status, u.name 
    FROM appointment a 
    JOIN user u ON a.user_id = u.id 
    WHERE a.dateApp = ?
");
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();

// Jika klik patient, ambil detail penuh
$patientDetails = null;
if ($selected_id) {
    $stmt = $conn->prepare("
        SELECT a.*, u.name, u.faculty_ptj, u.category 
        FROM appointment a 
        JOIN user u ON a.user_id = u.id 
        WHERE a.idApp = ?
    ");
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $patientDetails = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve/Reject Appointment</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 700px; margin: auto; }
        .btn { padding: 10px 15px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; }
        .approve { background-color: #4CAF50; color: white; }
        .reject { background-color: #f44336; color: white; }
        .appt-list a { display: block; margin: 5px 0; }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>Appointment Approval</h2>

    <form method="get" action="">
        <label>Select Date:</label>
        <input type="date" name="date" value="<?= htmlspecialchars($selected_date) ?>" />
        <input type="submit" value="View" />
    </form>

    <hr>
    <h3>Appointments on <?= htmlspecialchars($selected_date) ?></h3>
    <div class="appt-list">
        <?php foreach ($appointments as $appt): ?>
            <a href="?date=<?= $selected_date ?>&id=<?= $appt['idApp'] ?>">
                <?= $appt['timeApp'] ?> - <?= $appt['name'] ?> [<?= $appt['status'] ?>]
            </a>
        <?php endforeach; ?>
        <?php if (empty($appointments)) echo "<p>No appointments found.</p>"; ?>
    </div>

    <?php if ($patientDetails): ?>
        <hr>
        <h3>Patient Details</h3>
        <p><strong>Name:</strong> <?= $patientDetails['name'] ?></p>
        <p><strong>Date:</strong> <?= $patientDetails['dateApp'] ?></p>
        <p><strong>Time:</strong> <?= $patientDetails['timeApp'] ?></p>
        <p><strong>Faculty/PTJ:</strong> <?= $patientDetails['faculty_ptj'] ?></p>
        <p><strong>Category:</strong> <?= $patientDetails['category'] ?></p>

        <form method="post" action="">
            <input type="hidden" name="idApp" value="<?= $patientDetails['idApp'] ?>" />
            <button type="submit" name="status" value="APPROVED" class="btn approve">✅ Approve</button>
            <button type="submit" name="status" value="REJECTED" class="btn reject">❌ Reject</button>
        </form>
    <?php endif; ?>

    <?php if ($statusMsg): ?>
        <p class="msg"><?= $statusMsg ?></p>
    <?php endif; ?>
</div>
</body>
</html>
