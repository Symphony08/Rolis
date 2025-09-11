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
?>

<!-- CSS -->
<link rel="stylesheet" href="/Rolis/assets/css/style.css">
<link rel="stylesheet" href="/Rolis/assets/css/products.css">
<!-- JS -->
<script src="/Rolis/assets/js/script.js" defer></script>

<main class="main-content">
  <div class="container">
    <h1>📦 Products</h1>
    <a href="tambah_products.php" class="btn btn-blue">➕ Tambah Produk</a>

    <table class="customers-table">
      <thead>
        <tr>
          <th scope="col">No</th>
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
        <?php $no =1; ?>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <th scope="row"><?= $no++ ?></th>
              <td data-label="Merek"><?= htmlspecialchars($row['nama_merek']) ?></td>
              <td data-label="Nama"><?= htmlspecialchars($row['nama']) ?></td>
              <td data-label="Jenis"><?= htmlspecialchars($row['jenis']) ?></td>
              <td data-label="Deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></td>
              <td data-label="Warna"><?= htmlspecialchars($row['warna']) ?></td>
              <td data-label="Harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td data-label="Foto">
                <?php if (!empty($row['foto'])): ?>
                  <img src="<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" width="80">
                <?php else: ?>
                  <span class="no-data">Tidak ada</span>
                <?php endif; ?>
              </td>
              <td data-label="Aksi">
                <a href="edit_products.php?id=<?= $row['id_produk'] ?>" class="btn-green">✏ Ubah</a>
                <a href="hapus_products.php?id=<?= $row['id_produk'] ?>" class="btn-red" onclick="return confirm('Yakin hapus produk ini?')">🗑 Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" style="text-align:center;">Belum ada data produk</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include "../includes/footer.php"; ?>