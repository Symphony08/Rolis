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
    <div class="col-md-6 col-lg-5">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">✏ Edit Produk</h3>
          <p class="text-muted">Ubah informasi produk sepeda atau motor listrik.</p>
        </div>
        <form method="POST" enctype="multipart/form-data" novalidate>
          <div class="mb-3 row align-items-center">
            <label for="nama" class="col-sm-4 col-form-label fw-semibold">Nama Produk</label>
            <div class="col-sm-8">
              <input type="text" name="nama" id="nama" class="form-control rounded-3" value="<?= htmlspecialchars($data['nama']) ?>" required>
              <div class="invalid-feedback">Nama produk wajib diisi.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="merek_id" class="col-sm-4 col-form-label fw-semibold">Kategori</label>
            <div class="col-sm-8">
              <select name="merek_id" id="merek_id" class="form-select rounded-3" required>
                <option value="" disabled>-- Pilih kategori --</option>
                <?php mysqli_data_seek($merek, 0); while ($m = mysqli_fetch_assoc($merek)): ?>
                  <option value="<?= $m['id_merek'] ?>" <?= $data['merek_id'] == $m['id_merek'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['value']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Kategori wajib dipilih.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="jenis" class="col-sm-4 col-form-label fw-semibold">Jenis</label>
            <div class="col-sm-8">
              <select name="jenis" id="jenis" class="form-select rounded-3" required>
                <option value="MOTOR" <?= $data['jenis'] == 'MOTOR' ? 'selected' : '' ?>>MOTOR</option>
                <option value="SEPEDA" <?= $data['jenis'] == 'SEPEDA' ? 'selected' : '' ?>>SEPEDA</option>
              </select>
              <div class="invalid-feedback">Jenis wajib dipilih.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="warna" class="col-sm-4 col-form-label fw-semibold">Warna</label>
            <div class="col-sm-8">
              <input type="text" name="warna" id="warna" class="form-control rounded-3" value="<?= htmlspecialchars($data['warna']) ?>" required>
              <div class="invalid-feedback">Warna wajib diisi.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="harga" class="col-sm-4 col-form-label fw-semibold">Harga</label>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="number" name="harga" id="harga" class="form-control rounded-3" min="0" value="<?= htmlspecialchars($data['harga']) ?>" required>
                <div class="invalid-feedback">Harga wajib diisi dan tidak boleh negatif.</div>
              </div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="deskripsi" class="col-sm-4 col-form-label fw-semibold">Deskripsi</label>
            <div class="col-sm-8">
              <textarea name="deskripsi" id="deskripsi" class="form-control rounded-3" rows="3" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
              <div class="invalid-feedback">Deskripsi wajib diisi.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="foto" class="col-sm-4 col-form-label fw-semibold">Foto</label>
            <div class="col-sm-8">
              <input type="file" name="foto" id="foto" class="form-control rounded-3" accept="image/*">
              <div id="image-preview" class="mt-2">
                <?php if (!empty($data['foto'])): ?>
                  <img src="<?= htmlspecialchars($data['foto']) ?>" class="img-fluid rounded-3" style="max-width: 200px; max-height: 200px;" id="current-image">
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Update</button>
            <a href="index_products.php" class="btn btn-danger rounded-3 px-4 py-2 flex-grow-1">Kembali</a>
          </div>
        </form>
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