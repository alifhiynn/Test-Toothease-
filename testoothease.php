<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('connect.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $category = $_POST['category'];
    $gender = $_POST['gender'];
    $faculty_ptj = $_POST['faculty'];
    $ic_no = $_POST['number'];
    $student_staff_no = $_POST['matric'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (name, email, phone, category, faculty_ptj, gender, ic_no, student_staff_no, username, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $name, $email, $phone, $category, $faculty_ptj, $gender, $ic_no, $student_staff_no, $username, $hashed_password);

    if ($stmt->execute()) {
        echo "<p style='color:green; text-align:center;'>Sign up successful! Redirecting to login...</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 2000);
              </script>";
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="testoothease.css">
  <meta charset="UTF-8" />
  <title>Register Page</title>
  
</head>

<body>
  <div class="container">
    <form method="post" action="testoothease.php">
      <table>
        <tr>
          <td colspan="3">PLEASE FILL THE FORM BELOW</td>
        </tr>
        <tr>
          <td>Name:</td>
          <td colspan="2"><input type="text" name="name" required /></td>
        </tr>
        <tr>
          <td>Email:</td>
          <td colspan="2"><input type="email" name="email" required /></td>
        </tr>
        <tr>
        <tr>
          <td>No Staff/Student:</td>
          <td colspan="2"><input type="matric" name="matric" required /></td>
        </tr>
        <tr>
          <tr>
          <td>IC/Passport Number:</td>
            <td colspan="2">
               <input type="text" name="number" required pattern="\d{12}" title="IC number must be exactly 12 digits" />
            </td>      
             </tr>
        <tr>
          <td>Phone Number:</td>
          <td colspan="2"><input type="number" name="phone" required /></td>
        </tr>
        <tr>
          <td>Patient Category:</td>
          <td colspan="2">
            <label><input type="radio" name="category" value="Student" required /> Student</label>
            <label><input type="radio" name="category" value="Staff" /> Staff</label>
          </td>
        </tr>
        <tr>
          <td>Faculty/PTJ:</td>
          <td colspan="2">
            <select name="faculty" required>
              <option value="">Choose</option>
              <option>PEJABABAT NAIB CANSELOR, CANSELORI</option>
              <option>PEJABAT TIMBALAN NAIB CANSELOR (AKEDEMIK & ANTARABANGSA)</option>
              <option>PEJABAT TIMBALAN NAIB CANSELOR (PENYELIDIKAN & INOVASI)</option>
              <option>PUSAT PENGURUSAN PENYELIDIKAN & INOVASI</option>
              <option> PUSAT PENGURUSAN KOLABORASI RICE UTeM-MELAKA</option>
              <option>PEJABAT TIMBALAN NAIB CANSELOR (HAL EHWAL PELAJAR & ALUMNI)</option>
              <option>PEJABAT PENOLONG NAIB CANSELOR (STRATEGIK & KELESTARIAN GLOBAL)</option>
              <option>SEKOLAH PENGAJIAN SISWAZAH</option>
              <option>PEJABAT KETUA PEGAWAI DIGITAL</option>
              <option>PERPUSTAKAAN LAMAN HIKMAH</option>
              <option>PEJABAT BENDAHARI</option>
              <option>PEJABAT PENDAFTARAN</option>
              <option>FAKULTI TEKNOLOGI DAN KEJUTERAAN ELEKTRIK (FTKE)</option>
              <option>FAKULTI TEKNOLOGI DAN KEJUTERAAN ELEKTRONIK DAN KOMPUTER (FTKEK)</option>
              <option>FAKULTI TEKNOLOGI DAN KEJUTERAAN MEKANIKAL (FTKM)</option>
              <option>FAKULTI TEKNOLOGI DAN KEJUTERAAN INDUSTRI DAN PEMBUATAN (FTKIP)</option>
              <option>FAKULTI PENGURUSAN TEKNOLOGI DAN TEKNOUSAHAWANAN (FPTI)</option>
              <option>FAKULTI TEKNOLOGI MAKLUMAT DAN KOMUNIKASI (FTMK)</option>
              <option>PUSAT PEMBELAJARAN BAHASA</option>
              <option>INSTITUT PENGURUSAN TEKNOLOGI DAN KEUSAHAWANAN</option>
              <option>PUSAT ISLAM</option>
              <option>PEJABAT PENGURUSAN PEMBANGUNAN</option>
              <option>PEJABAT PENGURUSAN FASILITI</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>Gender:</td>
          <td colspan="2">
            <label><input type="radio" name="gender" value="female" required /> Female</label>
            <label><input type="radio" name="gender" value="male" /> Male</label>
          </td>
        </tr>
        <tr>
          <td>Username:</td>
          <td colspan="2"><input type="text" name="username" required /></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td colspan="2"><input type="password" name="password" required /></td>
        </tr>
        <tr>
          <td colspan="3" style="text-align: center;">
            <input type="submit" value="REGISTER" />
            <input type="reset" value="CLEAR FORM" />
          </td>
        </tr>
      </table>
    </form>
  </div>
  <script>
  document.querySelector("form").addEventListener("submit", function (e) {
  const category = document.querySelector('input[name="category"]:checked')?.value;
  const email = document.querySelector('input[name="email"]').value.trim();
  const ic = document.querySelector('input[name="number"]').value.trim(); // tambahkan ini

  const studentRegex = /^(D|B|M|P)\d{9}@student\.utem\.edu\.my$/i;
  const staffRegex = /^[a-zA-Z0-9._%+-]+@utem\.edu\.my$/i;

  if (!/^\d{12}$/.test(ic)) {
    alert("IC number must be exactly 12 digits.");
    e.preventDefault();
  } else if (category === "Student" && !studentRegex.test(email)) {
    alert("Please enter a valid Student UTeM email (e.g., D123456789@student.utem.edu.my)");
    e.preventDefault();
  } else if (category === "Staff" && !staffRegex.test(email)) {
    alert("Please enter a valid Staff UTeM email (e.g., johndoe@utem.edu.my)");
    e.preventDefault();
  }
});
</script>

</body>
</html>
