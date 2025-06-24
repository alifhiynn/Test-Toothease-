<?php
include('connect.php');
include('homepagedentist.php');
$result = mysqli_query($conn, "SELECT * FROM treatment_record");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treatment Record</title>
    <link rel ="stylesheet"  href = "treatmentrecord.css">
</head>
<body>
    <h2>TREATMENTS RECORDS</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Patient Name</th>
            <th>Treatment Date</th>
            <th>Treatment Details</th>
            <th>Medication Given</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['patient_name'] ?></td>
            <td><?= $row['treatment_date'] ?></td>
            <td><?= $row['treatment_details'] ?></td>
            <td><?= $row['medication_given'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

