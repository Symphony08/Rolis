<?php
session_start();
require_once "../includes/db.php";

$id = $_GET['id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM produk WHERE id_produk = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];

    $query = "UPDATE produk SET nama_produk=?, harga=?, stok=?, deskripsi=? WHERE id_produk=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sdisi", $nama_produk, $harga, $stok, $deskripsi, $id);
    mysqli_stmt_execute($stmt);

    header("Location: index_products.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <link rel="stylesheet" href="/Rolis/assets/css/style.css">
  <link rel="stylesheet" href="/Rolis/assets/css/products.css">
</head>
<body>
  <?php include "../includes/header.php"; ?>
  <?php include "../includes/sidebar.php"; ?>

  <main class="main-content">
    <div class="form-wrapper">
      <h1>✏ Edit Produk</h1>
      <form method="POST" class="product-form">
        <label for="nama_produk">Nama Produk</label>
        <input type="text" name="nama_produk" id="nama_produk" value="<?= htmlspecialchars($data['nama_produk']) ?>" required>

        <label for="harga">Harga</label>
        <input type="number" step="0.01" name="harga" id="harga" value="<?= $data['harga'] ?>" required>

        <label for="stok">Stok</label>
        <input type="number" name="stok" id="stok" value="<?= $data['stok'] ?>" required>

        <label for="deskripsi">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi"><?= htmlspecialchars($data['deskripsi']) ?></textarea>

        <div class="form-actions">
          <button type="submit" class="btn-update">Update</button>
          <a href="index_products.php" class="btn-red">Kembali</a>
        </div>
      </form>
    </div>
  </main>

  <?php include "../includes/footer.php"; ?>
</body>
</html>
