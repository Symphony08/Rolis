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
  <link rel="stylesheet" href="../../assets/css/style.css">
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
        <td><?= $row['id_pelanggan'] ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['no_hp']) ?></td>
        <td><?= htmlspecialchars($row['no_ktp']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td>
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