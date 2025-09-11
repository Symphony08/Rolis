<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php"; // koneksi database

// Query ambil semua data transaksi
$query = "
    SELECT t.*, p.nama AS nama_produk, c.nama AS nama_pelanggan
    FROM transaksi t
    JOIN produk p ON t.produk_id = p.id_produk
    JOIN pelanggan c ON t.pelanggan_id = c.id_pelanggan
    ORDER BY t.id_transaksi DESC
";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID Transaksi: " . $row['id_transaksi'] . "<br>";
    echo "Pelanggan: " . $row['nama_pelanggan'] . "<br>";
    echo "Produk: " . $row['nama_produk'] . "<br>";
    echo "Nomor Mesin: " . $row['nomor_mesin'] . "<br>";
    echo "Nomor Body: " . $row['nomor_body'] . "<br>";
    echo "Tanggal Garansi: " . $row['tanggal_garansi'] . "<br>";
    echo "<hr>";
}
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
    <a href="tambah_transaksi.php" class="btn btn-primary">➕ Tambah Transaksi</a>
  </div>

  <div class="table-responsive">
    <table id="transaksiTable" class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th class="text-center">No</th>
          <th>ID Transaksi</th>
          <th>ID Pelanggan</th>
          <th>ID Produk</th>
          <th>Nomor Mesin</th>
          <th>Nomor Body</th>
          <th>Tanggal Garansi</th>
          <th>Aksi</th>
        </tr>
      </thead>
    <tbody>
        <?php if (!empty($rows)): ?>
            <?php $no = 1; ?>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= $row['id_transaksi'] ?></td>
            <td><?= $row['pelanggan_id'] ?></td>
            <td><?= $row['produk_id'] ?></td>
            <td><?= htmlspecialchars($row['nomor_mesin']) ?></td>
            <td><?= htmlspecialchars($row['nomor_body']) ?></td>
            <td><?= date("d-m-Y", strtotime($row['tanggal_garansi'])) ?></td>
            <td>
                <a href="edit_transaksi.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-success btn-sm me-1">✏ Ubah</a>
                <a href="hapus_transaksi.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus transaksi ini?')">🗑 Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="8" class="text-center">Belum ada data transaksi</td>
        </tr>
        <?php endif; ?>
    </tbody>

    </table>
  </div>
</main>

<script>
  $(document).ready(function() {
    $('#transaksiTable').DataTable();
  });
</script>

<?php include "../includes/footer.php"; ?>
