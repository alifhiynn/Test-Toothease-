<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToothEase</title>
  <link rel="stylesheet" href="header.css">
</head>
<body>

  <nav class="navbar">
    <h1 class="logo">ToothEase</h1>
    
    <!-- Toggle button for burger menu -->
    <div class="burger" id="burger">
      &#9776;
    </div>

    <!-- Navigation links -->
    <div class="nav-links" id="navLinks">
      <a href="home.php">Home</a>
      <a href="appointment.php">Book Appointment</a>
      <a href="listappointment.php">List Appointment</a>
      <a href="feedback.php">Feedback</a>
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <script>
    const burger = document.getElementById('burger');
    const navLinks = document.getElementById('navLinks');

    burger.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });
  </script>

</body>
</html>
