<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php"; // koneksi database
?>

<main class="container mt-5 pt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>🔧 Layanan</h1>
    <a href="tambah_services.php" class="btn btn-primary">➕ Tambah Layanan</a>
  </div>

  <div class="alert alert-info">
    <p>Services management coming soon...</p>
  </div>
</main>

<?php include "../includes/footer.php"; ?>