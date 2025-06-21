<?php
include('connect.php');
include('homepagedentist.php');

// Initialize variables
$treatment_date = '';
$records = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['treatment_date'])) {
    $treatment_date = $_POST['treatment_date'];
    
    // Fetch appointments for the selected date
    $query = "SELECT a.idApp, u.name as patient_name, u.category, a.timeApp
              FROM appointment a
              JOIN user u ON a.user_id = u.id
              WHERE a.dateApp = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $treatment_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treatment Record</title>
    <link rel="stylesheet" href="treatment.css">
</head>
<body>
    <div class="treatment-container">
        <h1>TREATMENT RECORD</h1>
        <form method="POST">
            <div class="form-group">
                <label for="treatment_date">Please select date:</label>
                <input type="date" id="treatment_date" name="treatment_date" 
                       value="<?php echo htmlspecialchars($treatment_date); ?>" required>
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>

        <?php if (!empty($records)): ?>
            <div class="records-list">
                <?php foreach ($records as $record): ?>
                    <div class="record-entry">
                        <div class="record-fields">
                            <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($record['patient_name']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($record['category']); ?></p>
                            <p><strong>Time Appointment:</strong> <?php echo htmlspecialchars($record['timeApp']); ?></p>
                        </div>
                        <a href="make_record.php?id=<?php echo $record['idApp']; ?>" class="record-btn">Make Record</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <div class="no-records">
                <p>No appointments found for the selected date.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>