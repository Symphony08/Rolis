<?php
session_start();
require_once "../includes/db.php";

// Ambil semua data pelanggan
$result = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customers - Rolis Admin</title>
  <!-- CSS -->
    <link rel="stylesheet" href="/Rolis/assets/css/style.css">
    <link rel="stylesheet" href="/Rolis/assets/css/customers.css">
  <!-- JS -->
    <script src="/Rolis/assets/js/script.js" defer></script>
</head>
<body>
  <?php include "../includes/header.php"; ?>
  <?php include "../includes/sidebar.php"; ?>

  <main>
    <h1>👤 Customers</h1>
    <a href="tambah_customers.php" class="btn">➕ Tambah Customer</a>

    <table border="1" cellpadding="10" cellspacing="0">
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>No HP</th>
        <th>No KTP</th>
        <th>Alamat</th>
        <th>Aksi</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td data-label="ID"><?= $row['id_pelanggan'] ?></td>
        <td data-label="Nama"><?= htmlspecialchars($row['nama']) ?></td>
        <td data-label="No Hp"><?= htmlspecialchars($row['no_hp']) ?></td>
        <td data-label="No KTP"><?= htmlspecialchars($row['no_ktp']) ?></td>
        <td data-label="Alamat"><?= htmlspecialchars($row['alamat']) ?></td>
        <td data-label="Aksi">
          <a href="edit_customers.php?id=<?= $row['id_pelanggan'] ?>">✏ Edit</a> | 
          <a href="hapus_customers.php?id=<?= $row['id_pelanggan'] ?>" onclick="return confirm('Yakin mau hapus?')">🗑 Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </main>

  <?php include "../includes/footer.php"; ?>
</body>
</html>