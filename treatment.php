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
    
    // Fetch appointments for the selected date - updated to match your user table
    $query = "SELECT a.idApp, u.name, u.ic_no, u.category, a.timeApp, a.dateApp
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
    
    // Fetch appointment details - updated to match your user table
    $query = "SELECT a.idApp, u.name as patient_name, u.ic_no, a.timeApp, a.dateApp
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
    $diagnosis = $_POST['diagnosis'];
    $procedure_done = $_POST['procedure_done'];
    $treatment_date = $_POST['treatment_date'];
    
    // Insert treatment record
    $insert_query = "INSERT INTO treatment_record (appointment_id, diagnosis, procedure_done, treatment_date)
                     VALUES (?, ?, ?, ?)";
    
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("isss", $idApp, $diagnosis, $procedure_done, $treatment_date);
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
                                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($record['name']); ?></p>
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
                <p><strong>IC no:</strong> <?php echo htmlspecialchars($appointment_details['ic_no'] ?? ''); ?></p>
                <p><strong>Time Appointment:</strong> <?php echo htmlspecialchars($appointment_details['timeApp'] ?? ''); ?></p>
                <p><strong>Date Appointment:</strong> <?php echo htmlspecialchars($appointment_details['dateApp'] ?? ''); ?></p>
            </div>
            
            <form method="POST" class="treatment-form">
                <div class="form-group">
    <label for="diagnosis">Diagnosis:</label>
    <select id="diagnosis" name="diagnosis" required>
        <option value="">Select Diagnosis</option>
        <option value="Dental Caries">Dental Caries</option>
        <option value="Gingivitis">Gingivitis</option>
        <option value="Periodontitis">Periodontitis</option>
        <option value="Tooth Fracture">Tooth Fracture</option>
        <option value="Impacted Tooth">Impacted Tooth</option>
        <option value="Oral Thrush">Oral Thrush</option>
        <option value="Temporomandibular Joint Disorder">Temporomandibular Joint Disorder</option>
    </select>
                </div>            
                <div class="form-group">
                    <label for="procedure_done">Treatment Notes:</label>
                    <textarea id="procedure_done" name="procedure_done" rows="4" placeholder="Describe the procedure" required></textarea>
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