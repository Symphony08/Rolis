<?php
session_start();
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
  $harga = $_POST['harga'];

  $productController = new ProductController();
  $productController->create($_POST, $_FILES['foto']);

  $_SESSION['flash_message'] = 'Produk berhasil dibuat.';
  header("Location: index_products.php");
  exit;
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">Tambah Produk Baru</h3>
          <p class="text-muted">Isi informasi produk sepeda atau motor listrik.</p>
        </div>
        <form method="POST" enctype="multipart/form-data" novalidate onsubmit="prepareHarga()">
          <div class="mb-3 row align-items-center">
            <label for="nama" class="col-sm-4 col-form-label fw-semibold">Nama Produk</label>
            <div class="col-sm-8">
              <input type="text" name="nama" id="nama" class="form-control rounded-3" required>
              <div class="invalid-feedback">Nama produk wajib diisi.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="merek_id" class="col-sm-4 col-form-label fw-semibold">Kategori</label>
            <div class="col-sm-8">
              <select name="merek_id" id="merek_id" class="form-select rounded-3" required>
                <option value="" selected disabled>Pilih kategori</option>
                <?php while ($m = mysqli_fetch_assoc($merek)): ?>
                  <option value="<?= $m['id_merek'] ?>"><?= htmlspecialchars($m['value']) ?></option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Kategori wajib dipilih.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="jenis" class="col-sm-4 col-form-label fw-semibold">Jenis</label>
            <div class="col-sm-8">
              <select name="jenis" id="jenis" class="form-select rounded-3" required>
                <option value="MOTOR">MOTOR</option>
                <option value="SEPEDA">SEPEDA</option>
              </select>
              <div class="invalid-feedback">Jenis wajib dipilih.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="harga" class="col-sm-4 col-form-label fw-semibold">Harga</label>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" name="harga" id="harga" class="form-control rounded-3" value="0" required inputmode="numeric" pattern="[0-9,]*">
                <div class="invalid-feedback">Harga wajib diisi dan tidak boleh negatif.</div>
              </div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="deskripsi" class="col-sm-4 col-form-label fw-semibold">Deskripsi</label>
            <div class="col-sm-8">
              <textarea name="deskripsi" id="deskripsi" class="form-control rounded-3" rows="3" required></textarea>
              <div class="invalid-feedback">Deskripsi wajib diisi.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="foto" class="col-sm-4 col-form-label fw-semibold">Foto</label>
            <div class="col-sm-8">
              <input type="file" name="foto" id="foto" class="form-control rounded-3" accept="image/*">
              <div id="image-preview" class="mt-2"></div>
            </div>
          </div>
          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Tambah Produk</button>
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
      document.getElementById('image-preview').innerHTML = '';
    }
  });
</script>

<script>
  // Format input with comma separators while keeping the raw value intact
  const hargaInput = document.getElementById('harga');

  function formatNumberWithCommas(value) {
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function unformatNumber(value) {
    return value.replace(/,/g, "");
  }

  hargaInput.addEventListener('input', function(e) {
    const cursorPosition = this.selectionStart;
    const originalLength = this.value.length;

    // Remove all non-digit characters except commas
    let rawValue = unformatNumber(this.value.replace(/[^\d,]/g, ''));

    // Format the number with commas
    const formattedValue = formatNumberWithCommas(rawValue);

    this.value = formattedValue;

    // Adjust cursor position after formatting
    const newLength = formattedValue.length;
    const diff = newLength - originalLength;
    this.selectionStart = this.selectionEnd = cursorPosition + diff;
  });

  // Before form submit, convert formatted value back to raw number string
  function prepareHarga() {
    hargaInput.value = unformatNumber(hargaInput.value);
  }
</script>

<?php include "../includes/footer.php"; ?>