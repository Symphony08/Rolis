<?php
session_start();
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO produk (nama_produk, harga, stok, deskripsi) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sdis", $nama_produk, $harga, $stok, $deskripsi);
    mysqli_stmt_execute($stmt);

    header("Location: index_products.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link rel="stylesheet" href="/Rolis/assets/css/style.css">
  <link rel="stylesheet" href="/Rolis/assets/css/products.css">
</head>
<body>
  <?php include "../includes/header.php"; ?>
  <?php include "../includes/sidebar.php"; ?>

  <main class="main-content">
    <div class="form-wrapper">
      <h1>➕ Tambah Produk</h1>
      <form method="POST" class="product-form">
        <label for="nama_produk">Nama Produk</label>
        <input type="text" name="nama_produk" id="nama_produk" required>

        <label for="harga">Harga</label>
        <input type="number" step="0.01" name="harga" id="harga" required>

        <label for="stok">Stok</label>
        <input type="number" name="stok" id="stok" required>

        <label for="deskripsi">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi"></textarea>

        <div class="form-actions">
          <button type="submit" class="btn-green">Simpan</button>
          <a href="index_products.php" class="btn-red">Kembali</a>
        </div>
      </form>
    </div>
  </main>

  <?php include "../includes/footer.php"; ?>
</body>
</html>
