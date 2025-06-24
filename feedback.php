<?php
session_start();
include('connect.php');

// Semak login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userData = null;
$appointmentList = [];
$error = "";
$success = "";

// Dapatkan maklumat user
$stmt = $conn->prepare("SELECT name FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();

// Dapatkan appointment yang ada treatment & belum ada feedback
$stmt = $conn->prepare("SELECT a.idApp, a.dateApp, a.timeApp 
    FROM appointment a 
    INNER JOIN treatment_record t ON a.idApp = t.appointment_id 
    LEFT JOIN feedback f ON a.idApp = f.appointment_id 
    WHERE a.id = ? AND f.appointment_id IS NULL");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$appointmentList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Proses hntr feedback
if (isset($_POST['submit_feedback'])) {
    $appointment_id = $_POST['appointment_id'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO feedback (appointment_id, rating, message, timeFeedback) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $appointment_id, $rating, $message);
    if ($stmt->execute()) {
        $success = "Maklum balas berjaya dihantar.";
        // Refresh balik senarai lps hantar
        header("Location: feedback.php");
        exit();
    } else {
        $error = "Gagal hantar maklum balas: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Feedback</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <h2>Feedback Selepas Rawatan</h2>

    <p>Selamat datang, <strong><?= htmlspecialchars($userData['name']) ?></strong></p>

    <?php if ($success): ?>
        <p class="success">✅ <?= $success ?></p>
    <?php endif; ?>

    <?php if (count($appointmentList) == 0): ?>
        <p class="error">Tiada janji temu yang layak diberi maklum balas.</p>
    <?php else: ?>
        <form method="POST">
            <label>Pilih Janji Temu:</label>
            <select name="appointment_id" required>
                <?php foreach ($appointmentList as $app): ?>
                    <option value="<?= $app['idApp'] ?>">
                        <?= $app['dateApp'] ?> - <?= $app['timeApp'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Rating:</label>
            <select name="rating" required>
                <option value="">-- Pilih --</option>
                <option value="1">😡 Tidak Puas Hati</option>
                <option value="2">😟 Kurang Puas Hati</option>
                <option value="3">😐 Biasa</option>
                <option value="4">🙂 Puas Hati</option>
                <option value="5">😁 Sangat Puas Hati</option>
            </select>

            <label>Komen:</label>
            <textarea name="message" rows="4" placeholder="Tulis maklum balas anda..."></textarea>

            <button type="submit" name="submit_feedback">Hantar Maklum Balas</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
