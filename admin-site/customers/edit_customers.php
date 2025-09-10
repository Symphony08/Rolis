<?php
session_start();
require_once "../includes/db.php";

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = $id");
$data = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $no_ktp = $_POST['no_ktp'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE pelanggan SET nama=?, no_hp=?, no_ktp=?, alamat=? WHERE id_pelanggan=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $no_hp, $no_ktp, $alamat, $id);
    mysqli_stmt_execute($stmt);

    header("Location: index_customers.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Customer</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <?php include "../includes/header.php"; ?>
  <?php include "../includes/sidebar.php"; ?>

  <main>
    <h1>✏ Edit Customer</h1>
    <form method="POST">
      <label>Nama:</label><br>
      <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required><br><br>

      <label>No HP:</label><br>
      <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>" required><br><br>

      <label>No KTP:</label><br>
      <input type="text" name="no_ktp" value="<?= htmlspecialchars($data['no_ktp']) ?>" required><br><br>

      <label>Alamat:</label><br>
      <textarea name="alamat" required><?= htmlspecialchars($data['alamat']) ?></textarea><br><br>

      <button type="submit">Update</button>
    </form>
  </main>
</body>
</html>
