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

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h1 class="card-title mb-0">➕ Tambah Produk</h1>
        </div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="merek_id" class="form-label">Merek</label>
              <select name="merek_id" id="merek_id" class="form-select" required>
                <option value="">-- Pilih Merek --</option>
                <?php while ($m = mysqli_fetch_assoc($merek)): ?>
                  <option value="<?= $m['id_merek'] ?>"><?= htmlspecialchars($m['value']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" name="nama" id="nama" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="jenis" class="form-label">Jenis</label>
              <select name="jenis" id="jenis" class="form-select" required>
                <option value="MOTOR">MOTOR</option>
                <option value="SEPEDA">SEPEDA</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
              <label for="warna" class="form-label">Warna</label>
              <input type="text" name="warna" id="warna" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="harga" class="form-label">Harga</label>
              <input type="number" name="harga" id="harga" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="foto" class="form-label">Foto</label>
              <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
              <div id="image-preview" class="mt-2"></div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success">Simpan</button>
              <a href="index_products.php" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
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