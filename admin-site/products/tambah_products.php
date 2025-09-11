<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
require_once "../includes/db.php";
require_once "../controllers/ProductController.php";

use Adminsite\Controllers\ProductController;

// Ambil merek untuk dropdown
$merek = mysqli_query($conn, "SELECT * FROM merek ORDER BY value ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $merek_id = $_POST['merek_id'];
  $nama = $_POST['nama'];
  $jenis = $_POST['jenis'];
  $deskripsi = $_POST['deskripsi'];
  $warna = $_POST['warna'];
  $harga = $_POST['harga'];

  $productController = new ProductController();
  $productController->create($_POST, $_FILES['foto']);


  header("Location: index_products.php");
  exit;
}
?>

<link rel="stylesheet" href="/Rolis/assets/css/style.css">
<link rel="stylesheet" href="/Rolis/assets/css/products.css">

<main class="main-content">
  <div class="form-wrapper">
    <h1>➕ Tambah Produk</h1>
    <form method="POST" enctype="multipart/form-data" class="customer-form">
      <label for="merek_id">Merek</label>
      <select name="merek_id" id="merek_id" required>
        <option value="">-- Pilih Merek --</option>
        <?php while ($m = mysqli_fetch_assoc($merek)): ?>
          <option value="<?= $m['id_merek'] ?>"><?= htmlspecialchars($m['value']) ?></option>
        <?php endwhile; ?>
      </select>

      <label for="nama">Nama</label>
      <input type="text" name="nama" id="nama" required>

      <label for="jenis">Jenis</label>
      <select name="jenis" id="jenis" required>
        <option value="MOTOR">MOTOR</option>
        <option value="SEPEDA">SEPEDA</option>
      </select>

      <label for="deskripsi">Deskripsi</label>
      <textarea name="deskripsi" id="deskripsi" required></textarea>

      <label for="warna">Warna</label>
      <input type="text" name="warna" id="warna" required>

      <label for="harga">Harga</label>
      <input type="number" name="harga" id="harga" required>

      <label for="foto">Foto</label>
      <input type="file" name="foto" id="foto" accept="image/*">
      <div id="image-preview"></div>

      <div class="form-actions">
        <button type="submit" class="btn-green">Simpan</button>
        <a href="index_products.php" class="btn-red">Kembali</a>
      </div>
    </form>
  </div>
</main>

<script>
  document.getElementById('foto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.style.maxWidth = '200px';
        img.style.maxHeight = '200px';
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        preview.appendChild(img);
      };
      reader.readAsDataURL(file);
    } else {
      document.getElementById('image-preview').innerHTML = '';
    }
  });
</script>

<?php include "../includes/footer.php"; ?>