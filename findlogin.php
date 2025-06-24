<?php
session_start();
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $input_password = $_POST['password'];

    // ✅ Special case: Manual login untuk dentist norazah
    if ($username === 'norazah' && $input_password === '1234') {
        $_SESSION['username'] = $username;
        header("Location: homepagedentist.php");
        exit();
    }

    // 🔁 Normal login dari database
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Kalau password hash
        if (password_verify($input_password, $user['password'])) {
            $_SESSION['username'] = $username;

            if ($username == 'azah') {
                header("Location: homepagedentist.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            echo "Login failed: WRONG PASSWORD";
            echo "<meta http-equiv='refresh' content='3;URL=login.php'>";
        }
    } else {
        echo "Login failed: Username unexisted";
        echo "<meta http-equiv='refresh' content='3;URL=login.php'>";
    }
}
?>