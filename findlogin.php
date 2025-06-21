<?php
session_start();
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $input_password = $_POST['password'];

    // Cari user berdasarkan username
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kalau user wujud
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Semak password betul atau tidak
        if (password_verify($input_password, $user['password'])) {
            $_SESSION['username'] = $username;

            // Jika username = azah, redirect ke dentist dashboard
            if ($username == 'azah') {
                header("Location: homepagedentist.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            // Password salah
            echo "Login failed: WRONG PASSWORD";
            echo "<meta http-equiv='refresh' content='3;URL=login.php'>";
        }
    } else {
        // Username tak wujud
        echo "Login failed: Username unexisted";
        echo "<meta http-equiv='refresh' content='3;URL=login.php'>";
    }
}
?>
