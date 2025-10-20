<?php
session_start();
require_once "../includes/db.php";
require_once "../controllers/ServiceController.php";

use Adminsite\Controllers\ServiceController;

$serviceController = new ServiceController();

$pelangganList = $serviceController->getPelanggan();
$produkList = $serviceController->getProduk();
$transaksiList = $serviceController->getTransaksi();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $serviceController->create($_POST);
  $_SESSION['flash_message'] = 'Servis berhasil dibuat.';
  header("Location: index_services.php");
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
          <h3 class="fw-bold">Tambah Servis Baru</h3>
          <p class="text-muted">Isi informasi servis yang akan didaftarkan.</p>
        </div>
        <form method="POST" novalidate>
          <!-- ===== Transaksi ===== -->
          <div class="mb-3 row align-items-center">
            <label for="transaksi_id" class="col-sm-4 col-form-label fw-semibold">Transaksi (Opsional)</label>
            <div class="col-sm-8">
              <select name="transaksi_id" id="transaksi_id" class="form-select rounded-3">
                <option value="">Pilih Transaksi</option>
                <?php foreach ($transaksiList as $transaksi): ?>
                  <option value="<?= $transaksi['id_transaksi'] ?>" data-pelanggan-id="<?= $transaksi['pelanggan_id'] ?>" data-produk-id="<?= $transaksi['produk_id'] ?>"><?= htmlspecialchars($transaksi['nomor_mesin']) ?> - <?= htmlspecialchars($transaksi['pelanggan_nama']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <!-- ===== Pelanggan ===== -->
          <div class="mb-3 row align-items-center">
            <label for="pelanggan_id" class="col-sm-4 col-form-label fw-semibold">Pelanggan</label>
            <div class="col-sm-8">
              <select name="pelanggan_id" id="pelanggan_id" class="form-select rounded-3" required>
                <option value="">Pilih Pelanggan</option>
                <?php foreach ($pelangganList as $pelanggan): ?>
                  <option value="<?= $pelanggan['id_pelanggan'] ?>"><?= htmlspecialchars($pelanggan['nama']) ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Pelanggan wajib dipilih.</div>
            </div>
          </div>
          <!-- ===== Produk ===== -->
          <div class="mb-3 row align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Produk</label>
            <div class="col-sm-8">
              <!-- Toggle switch -->
              <div class="d-flex align-items-center mb-2">
                <span class="me-2">Pilih</span>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="produkToggleSwitch">
                </div>
                <span class="ms-2">Input Manual</span>
              </div>
              <!-- Dropdown produk -->
              <select name="produk_id" id="produk_id" class="form-select rounded-3" required>
                <option value="">Pilih Produk</option>
                <?php foreach ($produkList as $produk): ?>
                  <option value="<?= $produk['id_produk'] ?>">
                    <?= htmlspecialchars($produk['nama']) ?> (<?= htmlspecialchars($produk['jenis']) ?> - <?= htmlspecialchars($produk['merek']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Produk wajib dipilih.</div>
              <!-- Input manual produk -->
              <div id="manualProdukFields" class="d-none mt-3">
                <div class="mb-2">
                  <input type="text" name="nama_manual" class="form-control" placeholder="Nama Produk" required>
                </div>
                <div class="mb-2">
                  <select name="jenis_manual" class="form-select" required>
                    <option value="">Pilih Jenis</option>
                    <option value="MOTOR">Motor</option>
                    <option value="SEPEDA">Sepeda</option>
                  </select>
                </div>
                <div class="mb-2">
                  <input type="text" name="merek_manual" class="form-control" placeholder="Merek" required>
                </div>
                <div class="mb-2">
                  <input type="text" name="tipe_manual" class="form-control" placeholder="Tipe" required>
                </div>
                <div class="mb-2">
                  <input type="text" name="nomor_body_manual" class="form-control" placeholder="Nomor Body" required>
                </div>
                <div class="mb-2">
                  <input type="text" name="warna_manual" class="form-control" placeholder="Warna" required>
                </div>
              </div>
            </div>
          </div>
          <!-- ===== Keluhan ===== -->
          <div class="mb-3 row align-items-center">
            <label for="keluhan" class="col-sm-4 col-form-label fw-semibold">Keluhan</label>
            <div class="col-sm-8">
              <textarea name="keluhan" id="keluhan" class="form-control rounded-3" rows="3" required></textarea>
              <div class="invalid-feedback">Keluhan wajib diisi.</div>
            </div>
          </div>
          <!-- ===== Biaya ===== -->
          <div class="mb-3 row align-items-center">
            <label for="biaya" class="col-sm-4 col-form-label fw-semibold">Biaya</label>
            <div class="col-sm-8">
              <input type="number" name="biaya" id="biaya" class="form-control rounded-3" step="0.01" min="0">
            </div>
          </div>
          <!-- ===== Keterangan ===== -->
          <div class="mb-3 row align-items-center">
            <label for="keterangan" class="col-sm-4 col-form-label fw-semibold">Keterangan</label>
            <div class="col-sm-8">
              <textarea name="keterangan" id="keterangan" class="form-control rounded-3" rows="3"></textarea>
            </div>
          </div>
          <!-- ===== Status ===== -->
          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Tambah Servis</button>
            <a href="index_services.php" class="btn btn-danger rounded-3 px-4 py-2 flex-grow-1">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const transaksiSelect = document.getElementById('transaksi_id');
    const pelangganSelect = document.getElementById('pelanggan_id');
    const produkSelect = document.getElementById('produk_id');
    const produkToggleSwitch = document.getElementById('produkToggleSwitch');
    const manualProdukFields = document.getElementById('manualProdukFields');
    const form = document.querySelector('form');

    // Function to toggle produk input mode
    function toggleProdukMode() {
      if (produkToggleSwitch.checked) {
        // Input Manual mode
        produkSelect.classList.add('d-none');
        manualProdukFields.classList.remove('d-none');
        produkSelect.removeAttribute('required');
        // Add required to manual inputs
        manualProdukFields.querySelectorAll('input, select').forEach(el => el.setAttribute('required', 'required'));
      } else {
        // Pilih mode
        produkSelect.classList.remove('d-none');
        manualProdukFields.classList.add('d-none');
        produkSelect.setAttribute('required', 'required');
        // Remove required from manual inputs
        manualProdukFields.querySelectorAll('input, select').forEach(el => el.removeAttribute('required'));
        // Clear manual input values
        manualProdukFields.querySelectorAll('input, select').forEach(el => el.value = '');
      }
    }

    // Initial toggle
    toggleProdukMode();

    // Event listener for toggle switch
    produkToggleSwitch.addEventListener('change', toggleProdukMode);

    transaksiSelect.addEventListener('change', function() {
      const selectedOption = transaksiSelect.options[transaksiSelect.selectedIndex];
      const pelangganId = selectedOption.getAttribute('data-pelanggan-id');
      const produkId = selectedOption.getAttribute('data-produk-id');

      if (transaksiSelect.value) {
        // Overwrite pelanggan and produk selects with transaksi data
        pelangganSelect.value = pelangganId;
        produkSelect.value = produkId;

        // Disable pelanggan and produk selects
        pelangganSelect.disabled = true;
        produkSelect.disabled = true;

        // Hide and disable toggle switch and its labels
        produkToggleSwitch.style.display = 'none';
        produkToggleSwitch.disabled = true;
        // Hide the labels "Pilih" and "Input Manual"
        const pilihLabel = produkToggleSwitch.parentElement.previousElementSibling;
        const inputManualLabel = produkToggleSwitch.parentElement.nextElementSibling;
        if (pilihLabel) pilihLabel.style.display = 'none';
        if (inputManualLabel) inputManualLabel.style.display = 'none';

        // Ensure produk is in select mode and set value
        produkToggleSwitch.checked = false;
        toggleProdukMode();
        produkSelect.disabled = true;
        // Clear manual input values
        manualProdukFields.querySelectorAll('input, select').forEach(el => el.value = '');
      } else {
        // Enable pelanggan and produk selects
        pelangganSelect.disabled = false;
        produkSelect.disabled = false;

        // Show and enable toggle switch and its labels
        produkToggleSwitch.style.display = '';
        produkToggleSwitch.disabled = false;
        // Show the labels "Pilih" and "Input Manual"
        const pilihLabel = produkToggleSwitch.parentElement.previousElementSibling;
        const inputManualLabel = produkToggleSwitch.parentElement.nextElementSibling;
        if (pilihLabel) pilihLabel.style.display = '';
        if (inputManualLabel) inputManualLabel.style.display = '';
        
        // Clear pelanggan and produk selects
        pelangganSelect.value = '';
        produkSelect.value = '';
      }
    });

    form.addEventListener('submit', function() {
      if (transaksiSelect.value) {
        // Re-enable selects before submit so their values are included in POST
        pelangganSelect.disabled = false;
        produkSelect.disabled = false;
      }
    });
  });
</script>

<?php include "../includes/footer.php"; ?>
<script src="/Rolis/public/assets/js/servis.js"></script>