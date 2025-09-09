<?php
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php"; // koneksi database
require_once '../controllers/Controller.php';
require_once '../controllers/CustomerController.php';

use Adminsite\Controllers\CustomerController;
?>

<!-- CSS -->
<link rel="stylesheet" href="/Rolis/assets/css/style.css">
<link rel="stylesheet" href="/Rolis/assets/css/customers.css">
<!-- JS -->
<script src="/Rolis/assets/js/script.js" defer></script>

<main class="main-content">
  <div class="container">
    <h1>Data Customers</h1>
    <a href="tambah_customers.php" class="btn-blue">Tambah Data</a>

    <table class="customers-table">
      <thead>
        <tr>
          <th>ID Pelanggan</th>
          <th>Nama</th>
          <th>No. HP</th>
          <th>NIK</th>
          <th>Alamat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $controller = new CustomerController();
        $result = $controller->show();
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                        <td>{$row['id_pelanggan']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['no_hp']}</td>
                        <td>{$row['no_ktp']}</td>
                        <td>{$row['alamat']}</td>
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