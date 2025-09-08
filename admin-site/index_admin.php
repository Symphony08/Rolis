<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLE CSS -->
        <link rel="stylesheet" href="../assets/css/style.css">
    <!-- SCRIPT JS -->
        <script src="../assets/js/script.js" defer></script>
    
    <title>Rolis - Admin Dashboard</title>
</head>
<body>
  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="hamburger">&#9776;</div> <!-- hamburger -->
    <div class="logo">Sekolah XYZ</div>
  </nav>
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">Profil Sekolah</a></li>
      <li><a href="#">Visi dan Misi</a></li>
      <li class="mobile-dropdown">
        <a href="#" class="dropdown-toggle">Data Warga Sekolah ▼</a>
        <ul class="mobile-submenu">
          <li><a href="#">Guru</a></li>
          <li><a href="#">Murid</a></li>
        </ul>
      </li>
      <li><a href="#">Data Alumni</a></li>
      <li class="mobile-dropdown">
        <a href="#" class="dropdown-toggle">Galeri ▼</a>
        <ul class="mobile-submenu">
          <li><a href="#">Foto</a></li>
          <li><a href="#">Video</a></li>
        </ul>
      </li>
      <li><a href="#">Berita Terkini</a></li>
      <li><a href="#">Hubungi Kami</a></li>
  
  <!-- BUTTON LOGOUT -->
  <li>
    <a href="logout.php" class="btn-logout">Logout</a>
  </li>
  </ul>
  </aside>

  <!-- Sidebar (Mobile Menu) -->
  <div class="sidebar" id="sidebar">
    <ul>
      <li><a href="#home">Home</a></li>
      <li><a href="#about">Profil Sekolah</a></li>
      <li><a href="#">Visi dan Misi</a></li>
      <li class="mobile-dropdown">
      <a href="#" class="dropdown-toggle">Data Warga Sekolah ▾</a>
        <ul class="mobile-submenu">
          <li><a href="dataguru.php">Data Guru</a></li>
          <li><a href="datamurid.php">Data Murid</a></li>
        </ul>
      </li>
      <li><a href="#">Data Alumni</a></li>
      <li class="mobile-dropdown">
      <a href="#" class="dropdown-toggle">Galeri ▾</a>
        <ul class="mobile-submenu">
          <li><a href="foto.php">Foto</a></li>
          <li><a href="video.php">Video</a></li>
        </ul>
      </li>
      <li><a href="#">Berita Terkini</a></li>
      <li><a href="#">Hubungi Kami</a></li>
      
  <!-- BUTTON LOGOUT -->
  <li>
    <a href="logout.php" class="btn-logout">Logout</a>
  </li>
  </ul>
  </div>

</body>
</html>