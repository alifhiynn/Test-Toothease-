<?php
session_start();
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idApp'])) {
    
    $idApp = $_POST['idApp'];

    // Dapatkan nama patient & id user
    $stmtInfo = $conn->prepare("SELECT u.name, u.id FROM appointment a JOIN user u ON a.id = u.id WHERE a.idApp = ?");
    $stmtInfo->bind_param("i", $idApp);
    $stmtInfo->execute();
    $result = $stmtInfo->get_result();
    $data = $result->fetch_assoc();
    $stmtInfo->close();

    $patientName = $data['name'] ?? "Unknown";
    $userId = $data['id'] ?? 0;

    // Insert notification hanya guna id_user
    $message = "$patientName has cancelled their appointment.";
    
    $stmtN = $conn->prepare("INSERT INTO notification (message_noti, is_read, id) VALUES (?, 0, ?)");
    $stmtN->bind_param("si", $message, $userId);
    $stmtN->execute();
    $stmtN->close();

    // Update status appointment ke CANCELLED
    $stmt = $conn->prepare("UPDATE appointment SET status = 'CANCELLED' WHERE idApp = ?");
    $stmt->bind_param("i", $idApp);

    if ($stmt->execute()) {
        header("Location: listappointment.php?msg=cancel_success");
        exit;
    } else {
        echo "<script>
                alert('Failed to cancel appointment.');
                window.location.href = 'listappointment.php';
              </script>";
    }

    $stmt->close();
}
$conn->close();
?>
