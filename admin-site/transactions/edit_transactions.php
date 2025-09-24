<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php";
require_once "../controllers/TransactionController.php";

$transactionController = new Adminsite\Controllers\TransactionController();

$id = $_GET['id'];
$data = $transactionController->select("SELECT * FROM transaksi WHERE id_transaksi = $id")->fetch_assoc();

$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama ASC");
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama ASC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $transactionController->edit($id, $_POST);
  $_SESSION['flash_message'] = "Transaksi berhasil diperbarui!";
  header("Location: index_transactions.php");
  exit;
}
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">Edit Transaksi</h3>
          <p class="text-muted">Perbarui informasi transaksi.</p>
        </div>
        <form method="POST" novalidate>
          <div class="mb-3 row align-items-center">
            <label for="pelanggan_id" class="col-sm-4 col-form-label fw-semibold">Pelanggan</label>
            <div class="col-sm-8">
              <select name="pelanggan_id" id="pelanggan_id" class="form-select rounded-3" required>
                <option value="" disabled>Pilih pelanggan</option>
                <?php while ($c = mysqli_fetch_assoc($pelanggan)): ?>
                  <option value="<?= $c['id_pelanggan'] ?>" <?= $data['pelanggan_id'] == $c['id_pelanggan'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nama']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Pelanggan wajib dipilih.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="produk_id" class="col-sm-4 col-form-label fw-semibold">Produk</label>
            <div class="col-sm-8">
              <select name="produk_id" id="produk_id" class="form-select rounded-3" required>
                <option value="" disabled>Pilih produk</option>
                <?php while ($p = mysqli_fetch_assoc($produk)): ?>
                  <option value="<?= $p['id_produk'] ?>" <?= $data['produk_id'] == $p['id_produk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nama']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Produk wajib dipilih.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="warna" class="col-sm-4 col-form-label fw-semibold">Warna</label>
            <div class="col-sm-8">
              <input type="text" name="warna" id="warna" value="<?= $data['warna'] ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Warna wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="nomor_mesin" class="col-sm-4 col-form-label fw-semibold">Nomor Mesin</label>
            <div class="col-sm-8">
              <input type="text" name="nomor_mesin" id="nomor_mesin" value="<?= htmlspecialchars($data['nomor_mesin']) ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Nomor mesin wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="nomor_body" class="col-sm-4 col-form-label fw-semibold">Nomor Body</label>
            <div class="col-sm-8">
              <input type="text" name="nomor_body" id="nomor_body" value="<?= htmlspecialchars($data['nomor_body']) ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Nomor body wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="tanggal_transaksi" class="col-sm-4 col-form-label fw-semibold">Tanggal Transaksi</label>
            <div class="col-sm-8">
              <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" value="<?= $data['tanggal_transaksi'] ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Tanggal transaksi wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="tanggal_garansi" class="col-sm-4 col-form-label fw-semibold">Tanggal Berakhir Garansi</label>
            <div class="col-sm-8">
              <input type="date" name="tanggal_garansi" id="tanggal_garansi" value="<?= $data['tanggal_garansi'] ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Tanggal berakhir garansi wajib diisi.</div>
            </div>
          </div>

          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Update Transaksi</button>
            <a href="index_transactions.php" class="btn btn-danger rounded-3 px-4 py-2 flex-grow-1">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php include "../includes/footer.php"; ?>