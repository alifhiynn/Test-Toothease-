<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_notification'])) {
    $id_notification = $_POST['id_notification'];

    $stmt = $conn->prepare("DELETE FROM notification WHERE id_notification = ?");
    $stmt->bind_param("i", $id_notification);

    if ($stmt->execute()) {
        header("Location: viewnotification.php?msg=delete_success");
    } else {
        echo "<script>alert('Failed to delete notification.'); 
        window.location.href='viewnotification.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
