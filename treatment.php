<?php
include('connect.php');
include('homepagedentist.php');

// Initialize variables
$treatment_date = isset($_POST['treatment_date']) ? $_POST['treatment_date'] : '';
$records = [];
$show_record_form = false;
$appointment_details = [];
$treatment_record = [];

// Check if we're viewing a specific record
if (isset($_GET['record_id']) && is_numeric($_GET['record_id'])) {
    $record_id = $_GET['record_id'];
    
    // Fetch the specific treatment record
    $query = "SELECT tr.*, a.dateApp, a.timeApp, u.name as patient_name, u.ic_passport, u.category 
              FROM treatment_record tr
              JOIN appointment a ON tr.appointment_id = a.idApp
              JOIN user u ON a.user_id = u.id
              WHERE tr.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $treatment_record = $result->fetch_assoc();
    $stmt->close();
    
    if ($treatment_record) {
        $show_record_form = true;
        $appointment_details = [
            'patient_name' => $treatment_record['patient_name'],
            'ic_passport' => $treatment_record['ic_passport'],
            'timeApp' => $treatment_record['timeApp'],
            'dateApp' => $treatment_record['dateApp']
        ];
    }
}

// Process date selection form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['treatment_date'])) {
    $treatment_date = $_POST['treatment_date'];
    
    // Fetch appointments for the selected date
    $query = "SELECT a.idApp, u.name as patient_name, u.ic_passport, u.category, a.timeApp, a.dateApp
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

// Process treatment record form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['treatment_type'])) {
    $appointment_id = $_POST['appointment_id'];
    $treatment_type = $_POST['treatment_type'];
    $notes = $_POST['notes'];
    
    // Insert or update treatment record
    $query = "INSERT INTO treatment_record 
              (appointment_id, treatment_type, notes, treatment_date)
              VALUES (?, ?, ?, CURDATE())
              ON DUPLICATE KEY UPDATE
              treatment_type = VALUES(treatment_type),
              notes = VALUES(notes)";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $appointment_id, $treatment_type, $notes);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to prevent form resubmission
    header("Location: treatment_record.php?record_id=".$appointment_id."&success=1");
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
            <!-- Date Selection Form -->
            <form method="POST">
                <div class="form-group">
                    <label for="treatment_date">Please select date:</label>
                    <input type="date" id="treatment_date" name="treatment_date" 
                           value="<?php echo htmlspecialchars($treatment_date); ?>" required>
                </div>
                <button type="submit" class="submit-btn">Submit</button>
            </form>

            <!-- Appointments List -->
            <?php if (!empty($records)): ?>
                <div class="records-list">
                    <?php foreach ($records as $record): ?>
                        <div class="record-entry">
                            <div class="record-fields">
                                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($record['patient_name']); ?></p>
                                <p><strong>Category:</strong> <?php echo htmlspecialchars($record['category']); ?></p>
                                <p><strong>Time Appointment:</strong> <?php echo date('H:i', strtotime($record['timeApp'])); ?></p>
                            </div>
                            <a href="treatment_record.php?record_id=<?php echo $record['idApp']; ?>" class="record-btn">
                                <?php echo isset($treatment_record) ? 'View/Edit Record' : 'Make Record'; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <div class="no-records">
                    <p>No appointments found for the selected date.</p>
                </div>
            <?php endif; ?>
        
        <?php else: ?>
            <!-- Treatment Record Form -->
            <div class="patient-info">
                <p><strong>Please select date:</strong> <?php echo date('d/m/Y', strtotime($treatment_record['treatment_date'])); ?></p>
                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($appointment_details['patient_name']); ?></p>
                <p><strong>IC no/Passport:</strong> <?php echo htmlspecialchars($appointment_details['ic_passport']); ?></p>
                <p><strong>Time Appointment:</strong> <?php echo date('H:i', strtotime($appointment_details['timeApp'])); ?></p>
                <p><strong>Date Appointment:</strong> <?php echo date('d/m/Y', strtotime($appointment_details['dateApp'])); ?></p>
            </div>

            <hr>

            <form method="POST" class="treatment-form">
                <h2>Enter the record below</h2>
                <input type="hidden" name="appointment_id" value="<?php echo $treatment_record['appointment_id']; ?>">
                
                <div class="form-group">
                    <label for="treatment_type">Type of treatment:</label>
                    <select id="treatment_type" name="treatment_type" required>
                        <option value="">Select treatment</option>
                        <option value="Filling" <?php echo ($treatment_record['treatment_type'] == 'Filling') ? 'selected' : ''; ?>>Filling</option>
                        <option value="Extraction" <?php echo ($treatment_record['treatment_type'] == 'Extraction') ? 'selected' : ''; ?>>Extraction</option>
                        <option value="Cleaning" <?php echo ($treatment_record['treatment_type'] == 'Cleaning') ? 'selected' : ''; ?>>Cleaning</option>
                        <option value="Root Canal" <?php echo ($treatment_record['treatment_type'] == 'Root Canal') ? 'selected' : ''; ?>>Root Canal</option>
                        <option value="Braces" <?php echo ($treatment_record['treatment_type'] == 'Braces') ? 'selected' : ''; ?>>Braces</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes" rows="4"><?php echo htmlspecialchars($treatment_record['notes']); ?></textarea>
                </div>
                
                <button type="submit" class="save-btn">Save Record</button>
                <a href="treatment_record.php" class="cancel-btn">Cancel</a>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="success-message">Record saved successfully!</div>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>