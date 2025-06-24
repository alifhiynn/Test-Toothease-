<?php
include('connect.php');
session_start();
$search_name = $_GET['search_name'] ?? '';
$search_date = $_GET['search_date'] ?? '';

$sql = "SELECT f.*, u.name, u.ic_no, u.student_staff_no 
        FROM feedback f
        JOIN appointment a ON f.appointment_id = a.idApp
        JOIN user u ON a.id = u.id
        WHERE 1";

if (!empty($search_name)) {
    $sql .= " AND u.name LIKE '%" . $conn->real_escape_string($search_name) . "%'";
}
if (!empty($search_date)) {
    $sql .= " AND DATE(f.timeFeedback) = '" . $conn->real_escape_string($search_date) . "'";
}
$sql .= " ORDER BY f.timeFeedback DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Feedback - Dentist Panel</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>

<?php include 'headerdentist.php'; ?>

<div class="container">
    <h2>Feedback from Patients</h2>

    <form method="GET" action="" style="margin-bottom: 20px;">
        <label for="search_name">Search by Name:</label>
        <input type="text" name="search_name" id="search_name" value="<?= htmlspecialchars($search_name) ?>" />

        <label for="search_date">Search by Date:</label>
        <input type="date" name="search_date" id="search_date" value="<?= htmlspecialchars($search_date) ?>" />

        <button type="submit">Search</button>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Name</th>
                <th>IC No</th>
                <th>Matric/Staff No</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['ic_no']) ?></td>
                    <td><?= htmlspecialchars($row['student_staff_no']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td><?= $row['rating'] ?>/5</td>
                    <td><?= $row['timeFeedback'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No feedback available yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
