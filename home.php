<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ToothEase Home</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f8fb;
    }

    /* Navbar */
    .navbar {
      background-color: #2980b9;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .navbar h1 {
      color: white;
      margin: 0;
      font-size: 28px;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      padding: 10px 16px;
      margin-left: 10px;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .nav-links a:hover {
      background-color: #1f5f89;
    }

    /* Main content */
    .content {
      padding: 40px 20px;
      text-align: center;
    }

    .content h1 {
      font-size: 34px;
      color: #2c3e50;
      margin-bottom: 20px;
    }

    .content h2 {
      font-size: 24px;
      color: #34495e;
      margin: 20px 0 10px;
    }

    .content h3 {
      font-size: 18px;
      color: #555;
      font-weight: normal;
    }

    .content ol,
    .content ul {
      text-align: left;
      max-width: 700px;
      margin: 0 auto 20px;
      padding-left: 20px;
    }

    .content li {
      margin-bottom: 8px;
      line-height: 1.5;
    }

    .image-gallery {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 30px;
    }

    .image-gallery img {
      width: 250px;
      height: auto;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Button */
    .btn {
      display: inline-block;
      background-color: #3498db;
      color: white;
      padding: 14px 28px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 30px;
      text-decoration: none;
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #2471a3;
    }

    @media (max-width: 768px) {
      .navbar {
        flex-direction: column;
        align-items: flex-start;
      }
      .nav-links {
        margin-top: 10px;
      }
      .image-gallery {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>

<body>

  <!-- Navigation Bar -->
  <div class="navbar">
    <h1>ToothEase</h1>
    <div class="nav-links">
      <a href="homepage.php">Home</a>
      <a href="appointment.php">Book Appointment</a>
      <a href="listappointment.php">List Appointment</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h1>TOOTHEASE PERGIGIAN PKU</h1>

    <h2>Perkhidmatan pergigian di Pusat Kesihatan UTeM adalah untuk:</h2>
    <h3>
      <ol>
        <li>Pelajar UTeM</li>
        <li>Staf UTeM</li>
        <li>Keluarga Staf UTeM (dibuka hanya pada cuti semester sahaja)</li>
      </ol>
    </h3>

    <h2>Jenis Perkhidmatan:</h2>
    <h3>
      <ul>
        <li>Perkhidmatan sistem Online Appointment ini adalah untuk kes-kes walk-in sahaja.</li>
        <li>Bagi kes-kes kecemasan, hendaklah segera mendapatkan rawatan tanpa menempah slot temujanji.</li>
        <li>Kes-kes kecemasan pergigian adalah seperti berikut:</li>
      </ul>
      <ol>
        <li>Sakit gigi kuat dengan skor kesakitan 7-10</li>
        <li>Bengkak akibat sakit gigi disusuli simptom sistemik yang lain</li>
        <li>Penghasilan nanah akibat bengkak</li>
        <li>Kecederaan mulut dan gigi akibat trauma</li>
        <li>Pesakit dengan sistem imun yang rendah dengan kesakitan gigi melampau</li>
      </ol>
    </h3>

    <div class="image-gallery">
      <img src="image/jenis rawatan gigi.jpg" alt="Jenis Rawatan">
      <img src="image/cara memberus gigi.jpg" alt="Cara memberus gigi">
      <img src="image/macam mana nak jaga gigi.jpg" alt="Menjaga gigi">
    </div>

    <a href="appointment.php" class="btn">Book Now</a>
  </div>

</body>
</html>
