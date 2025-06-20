<?php
session_start();
include('connect.php');

$ic_no = "";
$student_staff_no = "";

// Terima data dari POST (search) atau GET (redirect dari cancel)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ic_no = $_POST['ic_no'];
    $student_staff_no = $_POST['student_staff_no'];
} elseif (isset($_GET['ic_no']) && isset($_GET['student_staff_no'])) {
    $ic_no = $_GET['ic_no'];
    $student_staff_no = $_GET['student_staff_no'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment List</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f8fb;
            padding: 40px 15px;
            color: #333;
        }

        .navbar {
            background-color: #00695c;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .navbar h1 {
            color: #fff;
            font-size: 1.8rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #c8f2ec;
        }

        h2, h3 {
            color: #00695c;
            margin-bottom: 15px;
        }

        form {
            margin-top: 10px;
            margin-bottom: 25px;
        }

        form input[type="text"] {
            padding: 10px;
            margin: 8px 5px 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 240px;
            font-size: 1rem;
        }

        form button {
            background-color: #00695c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #004d40;
        }

        .success-msg {
            color: green;
            margin-bottom: 20px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #00796b;
            color: white;
            font-weight: 600;
        }

        td {
            font-size: 0.95rem;
        }

        .cancel-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .cancel-btn:hover {
            background: #c0392b;
        }

        p {
            margin-top: 15px;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                margin-top: 10px;
            }

            form input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }

            table, th, td {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>ToothEase</h1>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="appointment.php">Book Appointment</a>
        <a href="listappointment.php">List Appointment</a>
        <a href="logout.php">Logout</a>
    </div>
</div> <!-- Tutup navbar -->

<h2>List Appointment</h2>

<?php
if (isset($_GET['msg']) && $_GET['msg'] == 'cancel_success') {
    echo "<p class='success-msg'>Appointment cancelled successfully.</p>";
}
?>

<form method="POST" action="listappointment.php" id="searchForm">
    <input type="text" name="ic_no" placeholder="Enter IC No" required value="<?php echo htmlspecialchars($ic_no); ?>">
    <input type="text" name="student_staff_no" placeholder="Enter Matric/Staff No" required value="<?php echo htmlspecialchars($student_staff_no); ?>">
    <button type="submit">Search</button>
</form>

<?php
if ($ic_no != "" && $student_staff_no != "") {
    $stmt = $conn->prepare("SELECT * FROM user WHERE ic_no = ? AND student_staff_no = ?");
    $stmt->bind_param("ss", $ic_no, $student_staff_no);
    $stmt->execute();
    $resultUser = $stmt->get_result();

    if ($resultUser->num_rows > 0) {
        $user = $resultUser->fetch_assoc();
        $user_id = $user['id'];

        $stmt2 = $conn->prepare("SELECT * FROM appointment WHERE user_id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $resultApp = $stmt2->get_result();

        if ($resultApp->num_rows > 0) {
            echo "<h3>Appointments for " . htmlspecialchars($user['name']) . "</h3>";
            echo "<table>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Name</th>
                        <th>IC</th>
                        <th>Matric No.</th>
                        <th>Action</th>
                    </tr>";

            while ($row = $resultApp->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['dateApp']) . "</td>
                        <td>" . htmlspecialchars($row['timeApp']) . "</td>
                        <td>" . htmlspecialchars($user['name']) . "</td>
                        <td>" . htmlspecialchars($user['ic_no']) . "</td>
                        <td>" . htmlspecialchars($user['student_staff_no']) . "</td>
                        <td>
                            <form method='POST' action='cancelappointment.php' onsubmit='return confirm(\"Are you sure you want to cancel this appointment?\");'>
                                <input type='hidden' name='idApp' value='" . $row['idApp'] . "'>
                                <input type='hidden' name='ic_no' value='" . htmlspecialchars($ic_no) . "'>
                                <input type='hidden' name='student_staff_no' value='" . htmlspecialchars($student_staff_no) . "'>
                                <button class='cancel-btn' type='submit'>Cancel Appointment</button>
                            </form>
                        </td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No appointments found for this user.</p>";
        }

        $stmt2->close();
    } else {
        echo "<p>User not found.</p>";
    }

    $stmt->close();
}
$conn->close();
?>

</body>
</html>
