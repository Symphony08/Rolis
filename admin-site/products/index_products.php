<?php 
include "../includes/header.php"; 
include "../includes/sidebar.php"; 
include "../includes/db.php"; // koneksi database
?>

<!-- CSS -->
<link rel="stylesheet" href="/Rolis/assets/css/products.css">
<link rel="stylesheet" href="/Rolis/assets/css/style.css">
<!-- JS -->
<script src="/Rolis/assets/js/script.js" defer></script>

<main class="main-content">
  <div class="container">
    <h1>Data Products</h1>
    <a href="tambah_customers.php" class="btn-blue">Tambah Data</a>

    <table class="customers-table">
      <thead>
        <tr>
          <th>Id Produk</th>
          <th>Id Merek</th>
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
        <?php
        $result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk ASC");
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['id_produk']}</td>
                        <td>{$row['merek_id']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['jenis']}</td>
                        <td>{$row['deskripsi']}</td>
                        <td>{$row['warna']}</td>
                        <td>{$row['harga']}</td>
                        <td>{$row['foto']}</td>
                        <td>
                          <button class='btn-green'>Ubah</button>
                          <button class='btn-red'>Hapus</button>
                        </td>
                      </tr>";
            }
        } 
        ?>
      </tbody>
    </table>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
