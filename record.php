<?php
include 'connect.php';

// --- 1. Dapatkan bilangan janji temu ikut bulan ---
$sql1 = "SELECT MONTH(dateApp) AS month, COUNT(*) AS total_appointments FROM appointment GROUP BY MONTH(dateApp)";
$result1 = $conn->query($sql1);

$months = ["1"=>"Jan", "2"=>"Feb", "3"=>"Mac", "4"=>"Apr", "5"=>"Mei", "6"=>"Jun", "7"=>"Jul", "8"=>"Ogos", "9"=>"Sept", "10"=>"Okt", "11"=>"Nov", "12"=>"Dis"];
$appointmentData = [];
while ($row = $result1->fetch_assoc()) {
    $appointmentData[] = [
        'month' => $months[$row['month']],
        'total' => $row['total_appointments']
    ];
}

// --- 2. Dapatkan bilangan rawatan ikut kategori ---
$sql2 = "SELECT u.category, COUNT(*) AS total_treatment
         FROM treatment_record t
         JOIN appointment a ON t.appointment_id = a.idApp
         JOIN user u ON a.id = u.id
         GROUP BY u.category";
$result2 = $conn->query($sql2);

$categories = [];
$categoryCounts = [];
while ($row = $result2->fetch_assoc()) {
    $categories[] = $row['category']; // 'Student' atau 'Staff'
    $categoryCounts[] = $row['total_treatment'];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>System Record - ToothEase</title>


    </title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 60%; margin-bottom: 40px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        .chart-container { width: 90%; max-width: 600px; margin-bottom: 50px; }
    </style>
</head>
<body>

<h2>Appointment Report by Month</h2>
<table>
    <tr><th>Bulan</th><th>Total Appointment</th></tr>
    <?php foreach ($appointmentData as $data): ?>
        <tr>
            <td><?= $data['month'] ?></td>
            <td><?= $data['total'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Total Treatment in Category</h2>
<div class="chart-container">
    <canvas id="categoryChart"></canvas>
</div>

<script>
const catCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(catCtx, {
    type: 'bar', // atau tukar ke 'pie'
    data: {
        labels: <?= json_encode($categories); ?>,
        datasets: [{
            label: 'Jumlah Rawatan',
            data: <?= json_encode($categoryCounts); ?>,
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)', // Student
                'rgba(255, 99, 132, 0.7)'  // Staff
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Total Treatment by Category'
            },
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
