<?php
session_start();
include('connect.php');

// Sql utk amik semua rekod rawatan bersama maklumat pesakit dan appointment
$sql = "SELECT 
            t.idTreatment, 
            t.diagnosis, 
            t.procedure_done, 
            t.treatment_date,
            a.dateApp, 
            a.timeApp, 
            u.name, 
            u.ic_no, 
            u.student_staff_no
        FROM treatment_record t
        JOIN appointment a ON t.appointment_id = a.idApp
        JOIN user u ON a.id = u.id
        ORDER BY t.treatment_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Treatment List</title>
    <link rel="stylesheet" href="treatmentrecord.css">
    
</head>
<body>

<h2>List of Recorded Treatments</h2>
<a href="homepagedentist.php" class="btn-home">← Kembali ke Laman Utama</a>

<table>
    <tr>
        <th>No.</th>
        <th>Name</th>
        <th>IC</th>
        <th>No Student/Staff</th>
        <th>Appointment Date</th>
        <th>Time</th>
        <th>Diagnosis</th>
        <th>Treatment</th>
        <th>Recorded Treatment Date</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$i}</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['ic_no']) . "</td>
                    <td>" . htmlspecialchars($row['student_staff_no']) . "</td>
                    <td>" . htmlspecialchars($row['dateApp']) . "</td>
                    <td>" . htmlspecialchars($row['timeApp']) . "</td>
                    <td>" . nl2br(htmlspecialchars($row['diagnosis'])) . "</td>
                    <td>" . nl2br(htmlspecialchars($row['procedure_done'])) . "</td>
                    <td>" . htmlspecialchars($row['treatment_date']) . "</td>
                </tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='9'>No record found.</td></tr>";
    }

    $conn->close();
    ?>
</table>

</body>
</html>
