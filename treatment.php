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
        <form action="process_treatment.php" method="POST">
            <div class="form-group">
                <label for="treatment_date">Please select date:</label>
                <input type="date" id="treatment_date" name="treatment_date" required>
            </div>
            
            <!-- Additional form fields can be added here -->
            
            <button type="submit" class="submit-btn">Submit Record</button>
        </form>
    </div>
</body>
</html>