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
  header("Location: index_transaksi.php");
  exit;
}
?>

<main class="container mt-5 pt-4">
  <h1>➕ Tambah Transaksi</h1>
  <form method="POST" class="row g-3">

    <div class="col-md-6">
      <label class="form-label">Pelanggan</label>
      <select name="pelanggan_id" class="form-select" required>
        <option value="">-- Pilih Pelanggan --</option>
        <?php while ($c = mysqli_fetch_assoc($pelanggan)): ?>
          <option value="<?= $c['id_pelanggan'] ?>"><?= htmlspecialchars($c['nama']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Produk</label>
      <select name="produk_id" class="form-select" required>
        <option value="">-- Pilih Produk --</option>
        <?php while ($p = mysqli_fetch_assoc($produk)): ?>
          <option value="<?= $p['id_produk'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Nomor Mesin</label>
      <input type="text" name="nomor_mesin" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Nomor Body</label>
      <input type="text" name="nomor_body" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Tanggal Garansi</label>
      <input type="date" name="tanggal_garansi" class="form-control" required>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="index_transaksi.php" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
</main>

<?php include "../includes/footer.php"; ?>
