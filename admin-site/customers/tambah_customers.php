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
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <?php include "../includes/header.php"; ?>
  <?php include "../includes/sidebar.php"; ?>

  <main>
    <h1>➕ Tambah Customer</h1>
    <form method="POST">
      <label>Nama:</label><br>
      <input type="text" name="nama" required><br><br>

      <label>No HP:</label><br>
      <input type="text" name="no_hp" required><br><br>

      <label>No KTP:</label><br>
      <input type="text" name="no_ktp" required><br><br>

      <label>Alamat:</label><br>
      <textarea name="alamat" required></textarea><br><br>

      <button type="submit">Simpan</button>
    </form>
  </main>
</body>
</html>
