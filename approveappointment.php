<?php
session_start();
include('connect.php');

$selected_date = $_GET['date'] ?? date('Y-m-d');
$selected_id = $_GET['id'] ?? null;
$search_name = $_GET['search_name'] ?? '';
$statusMsg = "";

// 1. Notis pembatalan janji temu ikut tarikh dipilih
$cancelledAppointments = [];
$cancel_stmt = $conn->prepare("
    SELECT a.timeApp, u.name 
    FROM appointment a
    JOIN user u ON a.id = u.id
    WHERE a.status = 'CANCELLED' AND a.dateApp = ?
");
$cancel_stmt->bind_param("s", $selected_date);
$cancel_stmt->execute();
$result_cancel = $cancel_stmt->get_result();
while ($row = $result_cancel->fetch_assoc()) {
    $cancelledAppointments[] = $row;
}
$cancel_stmt->close();

// 2. Jika submit approve/reject
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $app_id = $_POST['idApp'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE appointment SET status = ? WHERE idApp = ?");
    $stmt->bind_param("si", $status, $app_id);
    if ($stmt->execute()) {
        $statusMsg = "Status updated successfully!";
    }
    $stmt->close();
}

// 3. Papar senarai janji temu ikut tarikh & nama 
$appointments = [];
if (!empty($search_name)) {
    $like_name = '%' . $conn->real_escape_string($search_name) . '%';
    $stmt = $conn->prepare("
        SELECT a.idApp, a.dateApp, a.timeApp, a.status, u.name 
        FROM appointment a 
        JOIN user u ON a.id = u.id 
        WHERE a.dateApp = ? AND u.name LIKE ?
    ");
    $stmt->bind_param("ss", $selected_date, $like_name);
} else {
    $stmt = $conn->prepare("
        SELECT a.idApp, a.dateApp, a.timeApp, a.status, u.name 
        FROM appointment a 
        JOIN user u ON a.id = u.id 
        WHERE a.dateApp = ?
    ");
    $stmt->bind_param("s", $selected_date);
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();

// 4. Tunjuk maklumat penuh bila klik salah satu janji temu
$patientDetails = null;
if ($selected_id) {
    $stmt = $conn->prepare("
        SELECT a.*, u.name, u.faculty_ptj, u.category 
        FROM appointment a 
        JOIN user u ON a.id = u.id 
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
    <link rel="stylesheet" href="approveappointment.css">
    <style>
        .notice-box {
            border: 1px solid red;
            background: #ffe6e6;
            padding: 10px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 6px 12px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .approve { background-color: #4CAF50; color: white; }
        .reject { background-color: #f44336; color: white; }
        .back-home {
            background-color: #555;
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 5px;
        }
        .back-home:hover { background-color: #333; }
    </style>
</head>
<body>
<div class="container">
    <h2>Appointment Approval</h2>


    <!-- Form nk pilih tarikh dan nama -->

    <!-- Notis pembatalan janji temu -->
    <?php if (!empty($cancelledAppointments)): ?>
        <div class="notice-box">
            <strong>Patients who have canceled appointments (<?= htmlspecialchars($selected_date) ?>):</strong>
            <ul>
                <?php foreach ($cancelledAppointments as $cancel): ?>
                    <li><?= htmlspecialchars($cancel['timeApp']) ?> - <?= htmlspecialchars($cancel['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form pilih tarikh dan nama -->
    <form method="get" action="">
        <label>Select Date:</label>
        <input type="date" name="date" value="<?= htmlspecialchars($selected_date) ?>" />

        <label>Search Name:</label>
        <input type="text" name="search_name" value="<?= htmlspecialchars($search_name) ?>" />

        <input type="submit" value="View" />
    </form>

    <hr>
    <h3>Appointments on <?= htmlspecialchars($selected_date) ?></h3>
    <div class="appt-list">
        <?php foreach ($appointments as $appt): ?>
            <a href="?date=<?= urlencode($selected_date) ?>&search_name=<?= urlencode($search_name) ?>&id=<?= $appt['idApp'] ?>">
                <?= htmlspecialchars($appt['timeApp']) ?> - <?= htmlspecialchars($appt['name']) ?> [<?= htmlspecialchars($appt['status']) ?>]
            </a><br>
        <?php endforeach; ?>
        <?php if (empty($appointments)) echo "<p>No appointments found.</p>"; ?>
    </div>

    <?php if ($patientDetails): ?>
        <hr>
        <h3>Patient Details</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($patientDetails['name']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($patientDetails['dateApp']) ?></p>
        <p><strong>Time:</strong> <?= htmlspecialchars($patientDetails['timeApp']) ?></p>
        <p><strong>Faculty/PTJ:</strong> <?= htmlspecialchars($patientDetails['faculty_ptj']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($patientDetails['category']) ?></p>

        <form method="post" action="">
            <input type="hidden" name="idApp" value="<?= $patientDetails['idApp'] ?>" />
            <button type="submit" name="status" value="APPROVED" class="btn approve">Approve</button>
            <button type="submit" name="status" value="REJECTED" class="btn reject">Reject</button>
        </form>
    <?php endif; ?>

    <?php if ($statusMsg): ?>
        <p class="msg"><?= htmlspecialchars($statusMsg) ?></p>
    <?php endif; ?>

    <div style="margin-top: 20px;">
        <a href="homepagedentist.php" class="back-home">← Back to Home</a>
    </div>
</div>
</body>
</html>
