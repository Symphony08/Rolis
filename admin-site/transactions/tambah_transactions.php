<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php";
require_once "../controllers/TransactionController.php";

$transactionController = new Adminsite\Controllers\TransactionController();

// Ambil data pelanggan & produk untuk dropdown
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama ASC");
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama ASC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $transactionController->create($_POST);
  $_SESSION['flash_message'] = "Transaksi berhasil ditambahkan!";
  header("Location: index_transactions.php");
  exit;
}
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h1 class="card-title mb-0">➕ Tambah Transaksi</h1>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Pelanggan</label>
              <select name="pelanggan_id" class="form-select" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php while ($c = mysqli_fetch_assoc($pelanggan)): ?>
                  <option value="<?= $c['id_pelanggan'] ?>"><?= htmlspecialchars($c['nama']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Produk</label>
              <select name="produk_id" class="form-select" required>
                <option value="">-- Pilih Produk --</option>
                <?php while ($p = mysqli_fetch_assoc($produk)): ?>
                  <option value="<?= $p['id_produk'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Nomor Mesin</label>
              <input type="text" name="nomor_mesin" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Nomor Body</label>
              <input type="text" name="nomor_body" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Tanggal Berakhir Garansi</label>
              <input type="date" name="tanggal_garansi" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Tanggal Transaksi</label>
              <input type="date" name="tanggal_transaksi" class="form-control" required>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success">Simpan</button>
              <a href="index_transactions.php" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include "../includes/footer.php"; ?>