<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
require_once "../includes/db.php";
require_once "../controllers/ServiceController.php";

use Adminsite\Controllers\ServiceController;

$serviceController = new ServiceController();

$id = $_GET['id'];
$data = $serviceController->edit($id)->fetch_assoc();

$pelangganList = $serviceController->getPelanggan();
$produkList = $serviceController->getProduk();
$transaksiList = $serviceController->getTransaksi();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $serviceController->update($id, $_POST);
  $_SESSION['flash_message'] = 'Servis berhasil diperbarui.';
  header("Location: index_services.php");
  exit;
}
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-6">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">✏ Edit Servis</h3>
          <p class="text-muted">Perbarui informasi servis yang terdaftar.</p>
        </div>
        <form method="POST" novalidate>
          <div class="mb-3 row align-items-center">
            <label for="pelanggan_id" class="col-sm-4 col-form-label fw-semibold">Pelanggan</label>
            <div class="col-sm-8">
              <select name="pelanggan_id" id="pelanggan_id" class="form-select rounded-3" required>
                <option value="">Pilih Pelanggan</option>
                <?php foreach ($pelangganList as $pelanggan): ?>
                  <option value="<?= $pelanggan['id_pelanggan'] ?>" <?= $pelanggan['id_pelanggan'] == $data['pelanggan_id'] ? 'selected' : '' ?>><?= htmlspecialchars($pelanggan['nama']) ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Pelanggan wajib dipilih.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="produk_id" class="col-sm-4 col-form-label fw-semibold">Produk</label>
            <div class="col-sm-8">
              <select name="produk_id" id="produk_id" class="form-select rounded-3" required>
                <option value="">Pilih Produk</option>
                <?php foreach ($produkList as $produk): ?>
                  <option value="<?= $produk['id_produk'] ?>" <?= $produk['id_produk'] == $data['produk_id'] ? 'selected' : '' ?>><?= htmlspecialchars($produk['nama']) ?> (<?= htmlspecialchars($produk['jenis']) ?> - <?= htmlspecialchars($produk['merek']) ?>)</option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Produk wajib dipilih.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="transaksi_id" class="col-sm-4 col-form-label fw-semibold">Transaksi (Opsional)</label>
            <div class="col-sm-8">
              <select name="transaksi_id" id="transaksi_id" class="form-select rounded-3">
                <option value="">Pilih Transaksi</option>
                <?php foreach ($transaksiList as $transaksi): ?>
                  <option value="<?= $transaksi['id_transaksi'] ?>" data-pelanggan-id="<?= $transaksi['pelanggan_id'] ?>" data-produk-id="<?= $transaksi['produk_id'] ?>" <?= $transaksi['id_transaksi'] == $data['transaksi_id'] ? 'selected' : '' ?>><?= htmlspecialchars($transaksi['nomor_mesin']) ?> - <?= htmlspecialchars($transaksi['pelanggan_nama']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="keluhan" class="col-sm-4 col-form-label fw-semibold">Keluhan</label>
            <div class="col-sm-8">
              <textarea name="keluhan" id="keluhan" class="form-control rounded-3" rows="3" required><?= htmlspecialchars($data['keluhan']) ?></textarea>
              <div class="invalid-feedback">Keluhan wajib diisi.</div>
            </div>
          </div>
          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Update Servis</button>
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

    // Function to handle transaksi change
    function handleTransaksiChange() {
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
      } else {
        // Enable pelanggan and produk selects
        pelangganSelect.disabled = false;
        produkSelect.disabled = false;

        // Clear pelanggan and produk selects
        pelangganSelect.value = '';
        produkSelect.value = '';
      }
    }

    // Check initial state on load
    handleTransaksiChange();

    // Listen for changes
    transaksiSelect.addEventListener('change', handleTransaksiChange);
  });
</script>

<?php include "../includes/footer.php"; ?>