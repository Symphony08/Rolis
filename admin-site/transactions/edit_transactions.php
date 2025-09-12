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
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h1 class="card-title mb-0">✏ Sunting Transaksi</h1>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Pelanggan</label>
              <select name="pelanggan_id" class="form-select" required>
                <?php while ($c = mysqli_fetch_assoc($pelanggan)): ?>
                  <option value="<?= $c['id_pelanggan'] ?>" <?= $data['pelanggan_id'] == $c['id_pelanggan'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nama']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Produk</label>
              <select name="produk_id" class="form-select" required>
                <?php while ($p = mysqli_fetch_assoc($produk)): ?>
                  <option value="<?= $p['id_produk'] ?>" <?= $data['produk_id'] == $p['id_produk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nama']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Nomor Mesin</label>
              <input type="text" name="nomor_mesin" value="<?= htmlspecialchars($data['nomor_mesin']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Nomor Body</label>
              <input type="text" name="nomor_body" value="<?= htmlspecialchars($data['nomor_body']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Tanggal Garansi</label>
              <input type="date" name="tanggal_garansi" value="<?= $data['tanggal_garansi'] ?>" class="form-control" required>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Update</button>
              <a href="index_transactions.php" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include "../includes/footer.php"; ?>