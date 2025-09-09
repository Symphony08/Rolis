<?php
session_start();
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $no_ktp = $_POST['no_ktp'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO pelanggan (nama, no_hp, no_ktp, alamat) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nama, $no_hp, $no_ktp, $alamat);
    mysqli_stmt_execute($stmt);

    header("Location: index_customers.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Customer</title>
  <!-- CSS -->
    <link rel="stylesheet" href="/Rolis/assets/css/style.css">
    <link rel="stylesheet" href="/Rolis/assets/css/customers.css">
  <!-- JS -->
    <script src="/Rolis/assets/js/script.js" defer></script>
</head>
<body>
  <?php include "../includes/header.php"; ?>
  <?php include "../includes/sidebar.php"; ?>

  <main class="main-content">
    <div class="form-wrapper">
      <h1>➕ Tambah Customer</h1>
      <form method="POST" class="customer-form">
        <label for="nama">Nama</label>
        <input type="text" name="nama" id="nama" required>

        <label for="no_hp">No HP</label>
        <input type="text" name="no_hp" id="no_hp" required>

        <label for="no_ktp">No KTP</label>
        <input type="text" name="no_ktp" id="no_ktp" required>

        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat" required></textarea>

        <div class="form-actions">
          <button type="submit" class="btn-green">Simpan</button>
          <a href="index_customers.php" class="btn-red">Kembali</a>
        </div>
      </form>
    </div>
  </main>

  <?php include "../includes/footer.php"; ?>
</body>
</html>
