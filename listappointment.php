<?php
session_start();
include('connect.php');

// Semak sama ada user dah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Dapatkan maklumat user
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultUser = $stmt->get_result();
$user = $resultUser->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php include 'header.php'; ?>
    <title>Appointment List</title>
    <link rel="stylesheet" href="listappointment.css" />
</head>
<body>

<h2>List Appointment</h2>

<?php
if (isset($_GET['msg']) && $_GET['msg'] == 'cancel_success') {
    echo "<p class='success-msg'>Appointment cancelled successfully.</p>";
}
?>

<div class="user-info">
    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>IC:</strong> <?= htmlspecialchars($user['ic_no']) ?></p>
    <p><strong>Matric/Staff No:</strong> <?= htmlspecialchars($user['student_staff_no']) ?></p>
</div>

<?php
// Dapatkan senarai appointment user dgn status
$stmt2 = $conn->prepare("SELECT * FROM appointment WHERE id = ? AND status != 'Canceled'");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$resultApp = $stmt2->get_result();

if ($resultApp->num_rows > 0) {
    echo "<h3>Your Appointments</h3>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>";

    while ($row = $resultApp->fetch_assoc()) {
        $status = $row['status'] ?? 'Pending';

        echo "<tr>
                <td>" . htmlspecialchars($row['dateApp']) . "</td>
                <td>" . htmlspecialchars($row['timeApp']) . "</td>
                <td>" . htmlspecialchars($status) . "</td>
                <td>
                    <form method='POST' action='cancelappointment.php' onsubmit='return confirm(\"Are you sure you want to cancel this appointment?\");'>
                        <input type='hidden' name='idApp' value='" . $row['idApp'] . "' />
                        <button class='cancel-btn' type='submit'>Cancel</button>
                    </form>
                </td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "<p>You have no appointments yet.</p>";
}

$stmt2->close();
$conn->close();
?>

</body>
</html>
