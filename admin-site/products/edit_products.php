<?php
session_start();
include "../includes/db.php";
require_once "../controllers/ProductController.php";

use Adminsite\Controllers\ProductController;

$productController = new ProductController();

$id = $_GET['id'];
$data = $productController->edit($id)->fetch_assoc();

// Ambil data untuk dropdown
$merek = mysqli_query($conn, "SELECT * FROM merek ORDER BY value ASC");
$model = mysqli_query($conn, "SELECT * FROM model ORDER BY value ASC");
$warna = mysqli_query($conn, "SELECT * FROM warna ORDER BY value ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $productController->update($id, $_POST, $_FILES['foto']);
  $_SESSION['flash_message'] = 'Produk berhasil diperbarui.';
  header("Location: index_products.php");
  exit;
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-6">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">✏ Edit Produk</h3>
          <p class="text-muted">Ubah informasi produk sepeda atau motor listrik.</p>
        </div>
        <form method="POST" enctype="multipart/form-data" novalidate onsubmit="prepareHarga()">
          
          <!-- Merek -->
          <div class="mb-3 row align-items-center">
            <label for="merek_id" class="col-sm-4 col-form-label fw-semibold">Merek <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <select name="merek_id" id="merek_id" class="form-select rounded-3" required>
                <option value="" disabled>Pilih merek</option>
                <?php while ($m = mysqli_fetch_assoc($merek)): ?>
                  <option value="<?= $m['id_merek'] ?>" <?= $data['merek_id'] == $m['id_merek'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['value']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Merek wajib dipilih.</div>
            </div>
          </div>

          <!-- Model/Tipe -->
          <div class="mb-3 row align-items-center">
            <label for="model_id" class="col-sm-4 col-form-label fw-semibold">Model/Tipe <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <select name="model_id" id="model_id" class="form-select rounded-3" required>
                <option value="" disabled>Pilih model/tipe</option>
                <?php while ($mo = mysqli_fetch_assoc($model)): ?>
                  <option value="<?= $mo['id_model'] ?>" <?= $data['model_id'] == $mo['id_model'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mo['value']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Model/Tipe wajib dipilih.</div>
            </div>
          </div>

          <!-- Warna -->
          <div class="mb-3 row align-items-center">
            <label for="warna_id" class="col-sm-4 col-form-label fw-semibold">Warna <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <select name="warna_id" id="warna_id" class="form-select rounded-3" required>
                <option value="" disabled>Pilih warna</option>
                <?php while ($w = mysqli_fetch_assoc($warna)): ?>
                  <option value="<?= $w['id_warna'] ?>" <?= $data['warna_id'] == $w['id_warna'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($w['value']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Warna wajib dipilih.</div>
            </div>
          </div>

          <!-- Jenis -->
          <div class="mb-3 row align-items-center">
            <label for="jenis" class="col-sm-4 col-form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <select name="jenis" id="jenis" class="form-select rounded-3" required>
                <option value="MOTOR" <?= $data['jenis'] == 'MOTOR' ? 'selected' : '' ?>>MOTOR</option>
                <option value="SEPEDA" <?= $data['jenis'] == 'SEPEDA' ? 'selected' : '' ?>>SEPEDA</option>
              </select>
              <div class="invalid-feedback">Jenis wajib dipilih.</div>
            </div>
          </div>

          <!-- Harga -->
          <div class="mb-3 row align-items-center">
            <label for="harga" class="col-sm-4 col-form-label fw-semibold">Harga <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" name="harga" id="harga" class="form-control rounded-3" value="<?= htmlspecialchars($data['harga']) ?>" required inputmode="numeric" pattern="[0-9,]*">
                <div class="invalid-feedback">Harga wajib diisi.</div>
              </div>
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="mb-3 row align-items-center">
            <label for="deskripsi" class="col-sm-4 col-form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <textarea name="deskripsi" id="deskripsi" class="form-control rounded-3" rows="3" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
              <div class="invalid-feedback">Deskripsi wajib diisi.</div>
            </div>
          </div>

          <!-- Foto -->
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
        img.classList.add('rounded-3');
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
        img.classList.add('rounded-3');
        preview.appendChild(img);
      <?php endif; ?>
    }
  });
</script>

<script>
  const hargaInput = document.getElementById('harga');

  function formatNumberWithCommas(value) {
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function unformatNumber(value) {
    return value.replace(/,/g, "");
  }

  // Format initial value
  hargaInput.value = formatNumberWithCommas(unformatNumber(hargaInput.value));

  hargaInput.addEventListener('input', function(e) {
    const cursorPosition = this.selectionStart;
    const originalLength = this.value.length;

    let rawValue = unformatNumber(this.value.replace(/[^\d,]/g, ''));
    const formattedValue = formatNumberWithCommas(rawValue);

    this.value = formattedValue;

    const newLength = formattedValue.length;
    const diff = newLength - originalLength;
    this.selectionStart = this.selectionEnd = cursorPosition + diff;
  });

  function prepareHarga() {
    hargaInput.value = unformatNumber(hargaInput.value);
  }

  // Initialize Select2 for better UX
  $(document).ready(function() {
    $('#merek_id').select2({
      placeholder: "Pilih merek",
      allowClear: false,
      width: '100%'
    });

    $('#model_id').select2({
      placeholder: "Pilih model/tipe",
      allowClear: false,
      width: '100%'
    });

    $('#warna_id').select2({
      placeholder: "Pilih warna",
      allowClear: false,
      width: '100%'
    });

    $('#jenis').select2({
      placeholder: "Pilih jenis",
      allowClear: false,
      width: '100%',
      minimumResultsForSearch: Infinity
    });
  });
</script>

<?php include "../includes/footer.php"; ?>