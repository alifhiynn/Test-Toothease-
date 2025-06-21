<?php
include('connect.php');
include('homepagedentist.php');
<<<<<<< HEAD

// Initialize variables
$treatment_date = '';
$records = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['treatment_date'])) {
    $treatment_date = $_POST['treatment_date'];
    
    // Fetch complete treatment records with patient and appointment info
    $query = "SELECT t.*, a.timeApp, u.name as patient_name, u.category 
              FROM treatment_record t
              JOIN appointment a ON t.appointment_id = a.idApp
              JOIN user u ON a.user_id = u.id
              WHERE t.treatment_date = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $treatment_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    $stmt->close();
}
=======
$result = mysqli_query($conn, "SELECT * FROM treatment_record");
>>>>>>> 19d047ba8fee28a85753f7633e7ab554841985f1
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treatment Record</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>TREATMENT RECORD</h1>
<<<<<<< HEAD
        <form method="POST">
            <div class="form-group">
                <label for="treatment_date">Please select date:</label>
                <input type="date" id="treatment_date" name="treatment_date" 
                       value="<?php echo htmlspecialchars($treatment_date); ?>" required>
            </div>
            <button type="submit" class="submit-btn">Submit Record</button>
        </form>
    </div>

    <?php if (!empty($records)): ?>
        <div class="record-container">
            <?php foreach ($records as $record): ?>
                <div class="record-card">
                    <h3>Treatment #<?php echo htmlspecialchars($record['idTreatment']); ?></h3>
                    <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($record['patient_name']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($record['category']); ?></p>
                    <p><strong>Appointment Time:</strong> <?php echo htmlspecialchars($record['timeApp']); ?></p>
                    <p><strong>Diagnosis:</strong> <?php echo htmlspecialchars($record['diagnosis'] ?? 'Not recorded'); ?></p>
                    <p><strong>Procedure Done:</strong> <?php echo htmlspecialchars($record['procedure_done'] ?? 'Not recorded'); ?></p>
                    <a href="make_record.php?id=<?php echo $record['idTreatment']; ?>" class="record-btn">View/Edit Record</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div class="record-container">
            <div class="record-card">
                <h3>No records found for selected date</h3>
                <p>Please try a different date or add new records.</p>
            </div>
        </div>
    <?php endif; ?>
=======
        <form action="process_treatment.php" method="POST">
            <div class="form-group">
                <label for="treatment_date">Please select date:</label>
                <input type="date" id="treatment_date" name="treatment_date" required>
            </div>
            
            <!-- Additional form fields can be added here -->
            
            <button type="submit" class="submit-btn">Submit Record</button>
        </form>
    </div>
>>>>>>> 19d047ba8fee28a85753f7633e7ab554841985f1
</body>
</html>