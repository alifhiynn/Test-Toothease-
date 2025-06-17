<?php
session_start();
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $input_password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1){
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];

        //SEMAKAN username dan password
        if ($username == "azah" && $input_password == "1234") {
            header("Location: homepagedentist.php");
            exit();
        } else if ($user['password'] == $input_password) {
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Username not found'); window.location.href='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
