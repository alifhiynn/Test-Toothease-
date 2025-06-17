<?php
session_start();
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idApp'])) {
    $idApp = $_POST['idApp'];
    $ic_no = isset($_POST['ic_no']) ? $_POST['ic_no'] : "";
    $student_staff_no = isset($_POST['student_staff_no']) ? $_POST['student_staff_no'] : "";

    $stmt = $conn->prepare("DELETE FROM appointment WHERE idApp = ?");
    $stmt->bind_param("i", $idApp);

    if ($stmt->execute()) {
        // Redirect balik ke listappointment.php dengan pesan dan data input supaya list masih muncul
        header("Location: listappointment.php?msg=cancel_success&ic_no=" . urlencode($ic_no) . "&student_staff_no=" . urlencode($student_staff_no));
        exit;
    } else {
        // Kalau gagal delete, beri alert dan redirect balik
        echo "<script>
                alert('Failed to cancel appointment.');
                window.location.href = 'listappointment.php?ic_no=" . addslashes($ic_no) . "&student_staff_no=" . addslashes($student_staff_no) . "';
              </script>";
    }

    $stmt->close();
}
$conn->close();
?>
