<?php
include('connect.php');
session_start();

$userData = null;
$appointmentList = [];
$error = "";
$success = "";

if (isset($_POST['search'])) {
    $ic_no = $_POST['ic_no'];
    $matric = $_POST['matric'];

    // Semak user wujud atau tidak
    $stmt = $conn->prepare("SELECT id, name FROM user WHERE ic_no = ? AND student_staff_no = ?");
    $stmt->bind_param("ss", $ic_no, $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();

    if ($userData) {
        // Dapatkan senarai appointment yang ADA treatment
        $stmt = $conn->prepare("SELECT a.idApp, a.dateApp, a.timeApp 
            FROM appointment a 
            INNER JOIN treatment_record t ON a.idApp = t.appointment_id 
            WHERE a.id = ?");
        $stmt->bind_param("i", $userData['id']);
        $stmt->execute();
        $appointmentList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $error = "User cannot found.";
    }
}

if (isset($_POST['submit_feedback'])) {
    $appointment_id = $_POST['appointment_id'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO feedback (appointment_id, rating, message, timeFeedback) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $appointment_id, $rating, $message);
    if ($stmt->execute()) {
        $success = "Feedback Submit!";
    } else {
        $error = "Fail to send feedback: " . $stmt->error;
    }
    $stmt->close();
}
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
    <h2>Feedback After Treatment</h2>

    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <?php if (!$userData): ?>
        <form method="POST">
            <label>IC Number:</label>
            <input type="text" name="ic_no" required>

            <label>No Matrik / Staff:</label>
            <input type="text" name="matric" required>

            <button type="submit" name="search">Find Appointment</button>
        </form>
        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php else: ?>
        <h4>Selamat datang, <?= htmlspecialchars($userData['name']) ?></h4>

        <?php if (count($appointmentList) == 0): ?>
            <p class="error">No treatment is recorded for you to respond to.</p>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="ic_no" value="<?= htmlspecialchars($ic_no) ?>">
                <input type="hidden" name="matric" value="<?= htmlspecialchars($matric) ?>">

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
                    <option value="1">😡 Very Dissatisfied</option>
                    <option value="2">😟 Not Satisfied</option>
                    <option value="3">😐 Normal</option>
                    <option value="4">🙂 Satisfied</option>
                    <option value="5">😁 Very satisfied</option>
                </select>

                <label>Maklum Balas:</label>
                <textarea name="message" rows="4" placeholder="Tulis maklum balas anda..."></textarea>

                <button type="submit" name="submit_feedback">Hantar Feedback</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
