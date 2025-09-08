<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Website Image Slider</title>
    
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <!-- JS -->
    <script src="assets/js/script.js" defer></script>
    <!-- FontAwesome (wajib untuk icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  
  </head>
<body>
<!-- Navbar -->
<nav class="navbar">
  <ul class="nav-links">
    <li><a href="#home">Home</a></li>
    <li><a href="#about">About Me</a></li>
    <li><a href="proyek.html">Proyek Saya</a></li>
    <li><a href="#kritik">Kritik dan Saran</a></li>
  </ul>
  <div class="hamburger" id="hamburger">
    ☰
  </div>
</nav>
<!-- Sidebar (Mobile Menu) -->
<div class="sidebar" id="sidebar">
  <ul>
    <li><a href="#home">Home</a></li>
    <li><a href="#about">About Me</a></li>
    <li><a href="proyek.html">Proyek Saya</a></li>
    <li><a href="#kritik">Kritik dan Saran</a></li>
  </ul>
</div>

<!-- Landing Page -->
  <!-- Swiper / HOME -->
  <section class="main swiper mySwiper" id="home">
    <div class="wrapper swiper-wrapper">
      <div class="slide swiper-slide">
        <img src="assets/images/bg1A.jpg" alt="" class="image" />
        <div class="image-data">
          <span class="text">Hello, Welcome To My Portofolio</span>
        </div>
      </div>
      <div class="slide swiper-slide">
        <img src="assets/images/bg2.jpg" alt="" class="image" />
        <div class="image-data">
          <span class="text">TES</span>
          <h2>
            ABCDE <br />
            12345
          </h2>
        </div>
      </div>
      <div class="slide swiper-slide">
        <img src="assets/images/bg3.jpg" alt="" class="image" />
        <div class="image-data">
          <span class="text">Making Our coffee with lover.</span>
          <h2>
            Alluring and Fragrant <br />
            Coffee Aroma
          </h2>
        </div>
      </div>
    </div>
    <div class="swiper-button-next nav-btn"></div>
    <div class="swiper-button-prev nav-btn"></div>
    <div class="swiper-pagination"></div>
  </section>

  <!-- About Me -->
  <section class="landing" id="about">
    <div class="landing-container">
      <div class="landing-text">
        <h1>Hi, Saya <span>Muhammad Nur Fauzan Akmal</span></h1>
        <p>
          Saya seorang front end developer yang suka membuat website interaktif 
          dan responsif. Portofolio ini berisi karya-karya saya.
        </p>
        <a href="proyek.html" class="btn">Lihat Proyek Saya</a>
      </div>
      <div class="landing-photo">
        <img src="assets/images/tes-fotoprofil.jpg" alt="Foto Profil">
      </div>
    </div>
  </section>
  
  <!-- Kritik dan Saran -->
  <section class="kritik-saran" id="kritik">
    <div class="kritik-saran-container">
      <h2>Kritik dan Saran</h2>

      <form action="#">
        <label for="nama">Nama (Boleh Disamarkan Jika Anda Mau)</label>
          <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Anda" required>
        <label for="email">Email (Tidak Akan Dipublikasikan :Admin)</label>
          <input type="email" id="email" name="email" placeholder="Email" required>
        <label for="kritik-saran">Kritik dan Saran</label>
          <textarea id="kritik-saran" name="kritik-saran" rows="5" 
          placeholder="Tulis Kritik dan Saran Anda Disini..." required></textarea>
        <button type="submit" class="btn">Kirim</button>
      </form>

    </div>
  </section>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 1,
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>

  </body>
</html>