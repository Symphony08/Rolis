<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
require_once "../includes/db.php";

// Ambil semua data pelanggan
$result = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
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
    <h1>👤 Customers</h1>
    <a href="tambah_customers.php" class="btn btn-primary">➕ Tambah Customer</a>
  </div>

  <div class="table-responsive">
    <table id="customersTable" class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th class="text-center" scope="col">No</th>
          <th>Nama</th>
          <th>No HP</th>
          <th>No KTP</th>
          <th>Alamat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)): ?>
          <?php $no = 1; ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['no_hp']) ?></td>
              <td><?= htmlspecialchars($row['no_ktp']) ?></td>
              <td><?= htmlspecialchars($row['alamat']) ?></td>
              <td>
                <a href="edit_customers.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-success btn-sm me-1">✏ Edit</a>
                <a href="hapus_customers.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">🗑 Hapus</a>
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
    $('#customersTable').DataTable();
  });
</script>

<?php include "../includes/footer.php"; ?>