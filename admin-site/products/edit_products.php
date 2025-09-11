<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php";
require_once "../controllers/ProductController.php";

use Adminsite\Controllers\ProductController;

$productController = new ProductController();

$id = $_GET['id'];
$data = $productController->edit($id)->fetch_assoc();

// ambil semua merek
$merek = mysqli_query($conn, "SELECT * FROM merek ORDER BY value ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $productController->update($id, $_POST, $_FILES['foto']);
  header("Location: index_products.php");
  exit;
}
?>

<link rel="stylesheet" href="/Rolis/assets/css/style.css">
<link rel="stylesheet" href="/Rolis/assets/css/products.css">

<main class="main-content">
  <div class="form-wrapper">
    <h1>✏ Edit Produk</h1>
    <form method="POST" enctype="multipart/form-data" class="customer-form">
      <label for="merek_id">Merek</label>
      <select name="merek_id" id="merek_id" required>
        <?php while ($m = mysqli_fetch_assoc($merek)): ?>
          <option value="<?= $m['id_merek'] ?>" <?= $data['merek_id'] == $m['id_merek'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($m['value']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <label for="nama">Nama</label>
      <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

      <label for="jenis">Jenis</label>
      <select name="jenis" id="jenis" required>
        <option value="MOTOR" <?= $data['jenis'] == 'MOTOR' ? 'selected' : '' ?>>MOTOR</option>
        <option value="SEPEDA" <?= $data['jenis'] == 'SEPEDA' ? 'selected' : '' ?>>SEPEDA</option>
      </select>

      <label for="deskripsi">Deskripsi</label>
      <textarea name="deskripsi" id="deskripsi" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>

      <label for="warna">Warna</label>
      <input type="text" name="warna" id="warna" value="<?= htmlspecialchars($data['warna']) ?>" required>

      <label for="harga">Harga</label>
      <input type="number" name="harga" id="harga" value="<?= htmlspecialchars($data['harga']) ?>" required>

      <label for="foto">Foto</label>
      <?php if (!empty($data['foto'])): ?>
        <img src="../../uploads/<?= htmlspecialchars($data['foto']) ?>" width="100"><br>
      <?php endif; ?>
      <input type="file" name="foto" id="foto" accept="image/*">

      <div class="form-actions">
        <button type="submit" class="btn-update">Update</button>
        <a href="index_products.php" class="btn-red">Kembali</a>
      </div>
    </form>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
