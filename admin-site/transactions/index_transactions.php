<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php"; // koneksi database
require_once "../controllers/TransactionController.php";
$transactionController = new Adminsite\Controllers\TransactionController();

// Query ambil semua data transaksi
$rows = $transactionController->show()->fetch_all(MYSQLI_ASSOC);
?>

<main class="container mt-5 pt-4">
  <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['flash_message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📝 Transaksi</h1>
    <a href="tambah_transactions.php" class="btn btn-primary">➕ Tambah Transaksi</a>
  </div>

  <div class="table-responsive">
    <table id="transaksiTable" class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th class="text-center" scope="col">No</th>
          <th class="text-center">Pelanggan</th>
          <th class="text-center">Produk</th>
          <th class="text-center">Nomor Mesin</th>
          <th class="text-center">Nomor Body</th>
          <th class="text-center">Tanggal Garansi</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)): ?>
          <?php $no = 1; ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nama_produk']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nomor_mesin']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nomor_body']) ?></td>
              <td class="text-center"><?= date("d-m-Y", strtotime($row['tanggal_garansi'])) ?></td>
              <td class="text-center">
                <a href="edit_transactions.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-success btn-sm me-1">✏ Ubah</a>
                <a href="hapus_transactions.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus transaksi ini?')">🗑 Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>

    </table>
  </div>
</main>

<script>
  $(document).ready(function() {
    $('#transaksiTable').DataTable({
      "columnDefs": [{
        "orderable": false,
        "targets": [6]
      }]
    });
  });
</script>

<?php include "../includes/footer.php"; ?>