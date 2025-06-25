<?php
session_start();
include('connect.php');

// Proses delete jika button ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_notification'])) {
    $id_notification = $_POST['id_notification'];

    $stmt = $conn->prepare("DELETE FROM notification WHERE id_notification = ?");
    $stmt->bind_param("i", $id_notification);

    if ($stmt->execute()) {
        header("Location: viewnotification.php?msg=delete_success");
        exit;
    } else {
        echo "<script>alert('Failed to delete notification.'); 
        window.location.href='viewnotification.php';</script>";
    }

    $stmt->close();
}

// Paparan notification
$sql = "SELECT n.id_notification, n.message_noti, n.is_read, n.created_at, u.name, a.dateApp, a.timeApp
        FROM notification n
        LEFT JOIN user u ON n.id = u.id
        LEFT JOIN appointment a ON a.id = u.id AND a.status = 'CANCELLED'
        ORDER BY a.dateApp DESC, a.timeApp DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <link rel="stylesheet" href="viewnotification.css">
</head>
<body>

<h2>Notifications</h2>

<table>
    <tr>
        <th>Message</th>
        <th>Patient Name</th>
        <th>Appointment Date</th>
        <th>Appointment Time</th>
        <th>Created At</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status = $row['is_read'] ? "Read" : "Unread";

            echo "<tr>
                    <td>" . htmlspecialchars($row['message_noti']) . "</td>
                    <td>" . htmlspecialchars($row['name'] ?? '-') . "</td>
                    <td>" . htmlspecialchars($row['dateApp'] ?? '-') . "</td>
                    <td>" . htmlspecialchars($row['timeApp'] ?? '-') . "</td>
                    <td>" . htmlspecialchars($row['created_at']) . "</td>
                    <td>" . $status . "</td>
                    <td>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure to delete?\");'>
                            <input type='hidden' name='id_notification' value='" . $row['id_notification'] . "'>
                            <button type='submit' class='delete-btn'>Delete</button>
                        </form>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No notifications available.</td></tr>";
    }
    ?>
</table>

<?php
// Kemaskini semua notifikasi jadi sudah baca, hanya kalau bukan delete action
if (!isset($_POST['id_notification'])) {
    $conn->query("UPDATE notification SET is_read = 1");
}

$conn->close();
?>
 <!-- Butang kembali ke halaman utama dentist -->
    <div style="margin-right: 30px;">
        <a href="homepagedentist.php" class="btn back-home">← Back to Home</a>
    </div>

</body>
</html>
