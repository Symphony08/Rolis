<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php"; // koneksi database

// Ambil data produk + nama merek
$query = "SELECT p.*, m.value AS nama_merek
          FROM produk p
          JOIN merek m ON p.merek_id = m.id_merek
          ORDER BY p.id_produk DESC";
$result = mysqli_query($conn, $query);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
    <h1>📦 Products</h1>
    <a href="tambah_products.php" class="btn btn-primary">➕ Tambah Produk</a>
  </div>

  <div class="table-responsive">
    <table id="productsTable" class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th class="text-center" scope="col">No</th>
          <th>Merek</th>
          <th>Nama</th>
          <th>Jenis</th>
          <th>Deskripsi</th>
          <th>Warna</th>
          <th>Harga</th>
          <th>Foto</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)): ?>
          <?php $no = 1; ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama_merek']) ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['jenis']) ?></td>
              <td><?= htmlspecialchars($row['deskripsi']) ?></td>
              <td><?= htmlspecialchars($row['warna']) ?></td>
              <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td>
                <?php if (!empty($row['foto'])): ?>
                  <img src="<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" width="80" class="img-fluid">
                <?php else: ?>
                  <span class="text-muted">Tidak ada</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="edit_products.php?id=<?= $row['id_produk'] ?>" class="btn btn-success btn-sm me-1">✏ Ubah</a>
                <a href="hapus_products.php?id=<?= $row['id_produk'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus produk ini?')">🗑 Hapus</a>
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
    $('#productsTable').DataTable();
  });
</script>

<?php include "../includes/footer.php"; ?>