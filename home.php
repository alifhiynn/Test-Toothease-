<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ToothEase Home</title>

  <style>
  /* Reset dan base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f9fafd;
  color: #333;
  line-height: 1.6;
}

/* Navbar */
.navbar {
  background-color: #00695c; /* hijau gelap */
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  box-shadow: 0 2px 8px rgb(0 0 0 / 0.15);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.navbar h1 {
  font-weight: 700;
  font-size: 1.8rem;
  letter-spacing: 2px;
}

.nav-links a {
  color: white;
  text-decoration: none;
  margin-left: 2rem;
  font-weight: 600;
  transition: color 0.3s ease;
  font-size: 1rem;
}

.nav-links a:hover {
  color: #80cbc4; /* hijau muda */
}

/* Main content */
.content {
  max-width: 900px;
  margin: 2rem auto 4rem auto;
  background: white;
  padding: 2.5rem 3rem;
  border-radius: 8px;
  box-shadow: 0 8px 20px rgb(0 0 0 / 0.1);
}

.header-box h1 {
  color: #004d40;
  font-size: 2.5rem;
  margin-bottom: 0.3rem;
  font-weight: 800;
  letter-spacing: 1.5px;
  text-align: center;
}

.header-box label {
  display: block;
  text-align: center;
  font-size: 1.1rem;
  color: #00796b;
  margin-bottom: 1.5rem;
  font-weight: 600;
}

h2 {
  color: #00796b;
  font-size: 1.5rem;
  margin-top: 1.8rem;
  margin-bottom: 0.8rem;
  font-weight: 700;
  border-bottom: 2px solid #004d40;
  padding-bottom: 6px;
}

h3 {
  margin-left: 1rem;
  margin-bottom: 1.2rem;
  font-weight: 500;
  color: #444;
}

ol, ul {
  margin-left: 2rem;
  margin-bottom: 1rem;
  color: #555;
  font-size: 1rem;
}

/* Image gallery - disusun melintang, penuh lebar, tiada gap */
.image-gallery {
  display: flex;
  flex-wrap: nowrap;       /* melintang tanpa baris baru */
  width: 100%;             /* penuh lebar container */
  margin: 0;               /* tiada margin luar */
  padding: 0;              /* tiada padding luar */
  overflow-x: auto;        /* scroll jika gambar tak muat */
}

.image-gallery img {
  flex: 1 1 auto;          /* sama rata */
  max-height: 150px;       /* tinggi gambar kecil */
  width: auto;             /* maintain aspect ratio */
  object-fit: cover;       /* crop kalau perlu */
  border-radius: 0;        /* buang border radius */
  box-shadow: none;        /* tiada bayang */
  margin: 0;               /* tiada jarak antara gambar */
  cursor: pointer;
  transition: transform 0.3s ease;
}

.image-gallery img:hover {
  transform: scale(1.05);
}

/* Button */
.btn {
  display: block;
  width: max-content;
  background-color: #00796b;
  color: white;
  text-decoration: none;
  padding: 12px 28px;
  border-radius: 25px;
  font-weight: 700;
  font-size: 1.1rem;
  margin: 3rem auto 0 auto;
  box-shadow: 0 5px 15px rgb(0 121 107 / 0.4);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
  text-align: center;
}

.btn:hover {
  background-color: #004d40;
  box-shadow: 0 8px 20px rgb(0 77 64 / 0.6);
}

/* Responsive */
@media (max-width: 768px) {
  .navbar {
    flex-direction: column;
    align-items: flex-start;
  }

  .nav-links {
    margin-top: 0.8rem;
  }

  .image-gallery {
    flex-wrap: nowrap;
    overflow-x: auto;
  }

  .image-gallery img {
    max-height: 120px;
  }
}

  </style>

</head>

<body>

  <!-- Navigation Bar -->
  <div class="navbar">
    <h1>ToothEase</h1>
    <div class="nav-links">
      <a href="home.php">Home</a>
      <a href="appointment.php">Book Appointment</a>
      <a href="listappointment.php">List Appointment</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="header-box">
    <h1>SELAMAT DATANG KE TOOTHEASE</h1>
    <label>SISTEM PERGIGIAN PUSAT KESIHATAN UTeM</label>

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
