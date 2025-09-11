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
          <th class="text-center">Merek</th>
          <th class="text-center">Nama</th>
          <th class="text-center">Jenis</th>
          <th class="text-center">Deskripsi</th>
          <th class="text-center">Warna</th>
          <th class="text-center">Harga</th>
          <th class="text-center">Foto</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)): ?>
          <?php $no = 1; ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nama_merek']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nama']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['jenis']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['deskripsi']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['warna']) ?></td>
              <td class="text-center">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td class="text-center">
                <?php if (!empty($row['foto'])): ?>
                  <img src="<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" width="80" class="img-fluid">
                <?php else: ?>
                  <span class="text-muted">Tidak ada</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
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
    $('#productsTable').DataTable({
      "columnDefs": [{
        "orderable": false,
        "targets": [7, 8]
      }]
    });
  });
</script>

<?php include "../includes/footer.php"; ?>