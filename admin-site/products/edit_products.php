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
  $_SESSION['flash_message'] = 'Produk berhasil diperbarui.';
  header("Location: index_products.php");
  exit;
}
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h1 class="card-title mb-0">✏ Edit Produk</h1>
        </div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="merek_id" class="form-label">Merek</label>
              <select name="merek_id" id="merek_id" class="form-select" required>
                <?php while ($m = mysqli_fetch_assoc($merek)): ?>
                  <option value="<?= $m['id_merek'] ?>" <?= $data['merek_id'] == $m['id_merek'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['value']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="jenis" class="form-label">Jenis</label>
              <select name="jenis" id="jenis" class="form-select" required>
                <option value="MOTOR" <?= $data['jenis'] == 'MOTOR' ? 'selected' : '' ?>>MOTOR</option>
                <option value="SEPEDA" <?= $data['jenis'] == 'SEPEDA' ? 'selected' : '' ?>>SEPEDA</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <div class="mb-3">
              <label for="warna" class="form-label">Warna</label>
              <input type="text" name="warna" id="warna" class="form-control" value="<?= htmlspecialchars($data['warna']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="harga" class="form-label">Harga</label>
              <input type="number" name="harga" id="harga" class="form-control" value="<?= htmlspecialchars($data['harga']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="foto" class="form-label">Foto</label>
              <div id="image-preview" class="mb-2">
                <?php if (!empty($data['foto'])): ?>
                  <img src="<?= htmlspecialchars($data['foto']) ?>" class="img-thumbnail" style="max-width: 200px; max-height: 200px;" id="current-image">
                <?php endif; ?>
              </div>
              <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Update</button>
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
      const preview = document.getElementById('image-preview');
      preview.innerHTML = '';
      <?php if (!empty($data['foto'])): ?>
        const img = document.createElement('img');
        img.src = '<?= htmlspecialchars($data['foto']) ?>';
        img.style.maxWidth = '200px';
        img.style.maxHeight = '200px';
        preview.appendChild(img);
      <?php endif; ?>
    }
  });
</script>

<?php include "../includes/footer.php"; ?>