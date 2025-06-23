<?php
include('connect.php');
include('homepagedentist.php');

// Initialize variables
$treatment_date = '';
$records = [];
$show_record_form = false;
$appointment_details = [];

// Process date selection form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['treatment_date'])) {
    $treatment_date = $_POST['treatment_date'];
    
    // Fetch appointments for the selected date
    $query = "SELECT a.idApp, u.name as patient_name, u.ic_passport, u.category, a.timeApp, a.dateApp
              FROM appointment a
              JOIN user u ON a.id = u.id
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

// Check if we're viewing a specific appointment record
if (isset($_GET['id'])) {
    $idApp = intval($_GET['id']);
    $show_record_form = true;
    
    // Fetch appointment details
    $query = "SELECT a.idApp, u.name as patient_name, u.ic_passport, a.timeApp, a.dateApp
              FROM appointment a
              JOIN user u ON a.id = u.id
              WHERE a.idApp = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idApp);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment_details = $result->fetch_assoc();
    $stmt->close();
}

// Process treatment record submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_record'])) {
    $idApp = intval($_POST['idApp']);
    $treatment_type = $_POST['treatment_type'];
    $notes = $_POST['notes'];
    $treatment_date = $_POST['treatment_date'];
    
    // Insert treatment record
    $insert_query = "INSERT INTO treatment_records (idApp, treatment_type, notes, treatment_date)
                     VALUES (?, ?, ?, ?)";
    
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("isss", $idApp, $treatment_type, $notes, $treatment_date);
    $insert_stmt->execute();
    $insert_stmt->close();
    
    // Redirect back to the date selection
    header("Location: treatment.php?date=" . urlencode($treatment_date));
    exit();
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
        
        <?php if (!$show_record_form): ?>
            <!-- Date selection form -->
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
                            <a href="?id=<?php echo $record['idApp']; ?>&date=<?php echo urlencode($treatment_date); ?>" class="record-btn">Make Record</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <div class="no-records">
                    <p>No appointments found for the selected date.</p>
                </div>
            <?php endif; ?>
        
        <?php else: ?>
            <!-- Treatment record form -->
            <div class="patient-info">
                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($appointment_details['patient_name'] ?? ''); ?></p>
                <p><strong>IC no/Passport:</strong> <?php echo htmlspecialchars($appointment_details['ic_passport'] ?? ''); ?></p>
                <p><strong>Time Appointment:</strong> <?php echo htmlspecialchars($appointment_details['timeApp'] ?? ''); ?></p>
                <p><strong>Date Appointment:</strong> <?php echo htmlspecialchars($appointment_details['dateApp'] ?? ''); ?></p>
            </div>
            
            <form method="POST" class="treatment-form">
                <input type="hidden" name="idApp" value="<?php echo $appointment_details['idApp'] ?? ''; ?>">
                <input type="hidden" name="treatment_date" value="<?php echo $appointment_details['dateApp'] ?? ''; ?>">
                
                <div class="form-group">
                    <label for="treatment_type">Type of treatment:</label>
                    <select id="treatment_type" name="treatment_type" required>
                        <option value="">Select Treatment</option>
                        <option value="Filling">Filling</option>
                        <option value="Extraction">Extraction</option>
                        <option value="Cleaning">Cleaning</option>
                        <option value="Root Canal">Root Canal</option>
                        <option value="Checkup">Checkup</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes" rows="4" placeholder="Input text"></textarea>
                </div>
                
                <div class="form-actions">
                    <a href="?date=<?php echo urlencode($appointment_details['dateApp'] ?? ''); ?>" class="cancel-btn">Cancel</a>
                    <button type="submit" name="save_record" class="submit-btn">Save Record</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>