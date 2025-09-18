<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
require_once "../includes/db.php";
require_once "../controllers/CustomerController.php";
$customerController = new Adminsite\Controllers\CustomerController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $_POST['nama'];
  $no_hp = $_POST['no_hp'];
  $no_ktp = $_POST['no_ktp'];
  $alamat = $_POST['alamat'];

  $customerController->create($_POST);

  $_SESSION['flash_message'] = 'Customer berhasil dibuat.';
  header("Location: index_customers.php");
  exit;
}
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">Tambah Pelanggan Baru</h3>
          <p class="text-muted">Isi informasi pelanggan yang akan didaftarkan.</p>
        </div>
        <form method="POST" novalidate>
          <div class="mb-3 row align-items-center">
            <label for="nama" class="col-sm-4 col-form-label fw-semibold">Nama</label>
            <div class="col-sm-8">
              <input type="text" name="nama" id="nama" class="form-control rounded-3" required>
              <div class="invalid-feedback">Nama wajib diisi.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="no_hp" class="col-sm-4 col-form-label fw-semibold">No HP</label>
            <div class="col-sm-8">
              <input type="text" name="no_hp" id="no_hp" class="form-control rounded-3" required>
              <div class="invalid-feedback">No HP wajib diisi.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="no_ktp" class="col-sm-4 col-form-label fw-semibold">No KTP</label>
            <div class="col-sm-8">
              <input type="text" name="no_ktp" id="no_ktp" class="form-control rounded-3" required>
              <div class="invalid-feedback">No KTP wajib diisi.</div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <label for="alamat" class="col-sm-4 col-form-label fw-semibold">Alamat</label>
            <div class="col-sm-8">
              <textarea name="alamat" id="alamat" class="form-control rounded-3" rows="3" required></textarea>
              <div class="invalid-feedback">Alamat wajib diisi.</div>
            </div>
          </div>
          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Tambah Pelanggan</button>
            <a href="index_customers.php" class="btn btn-danger rounded-3 px-4 py-2 flex-grow-1">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php include "../includes/footer.php"; ?>