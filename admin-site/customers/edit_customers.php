<?php
session_start();
require_once "../includes/db.php";
require_once "../controllers/CustomerController.php";
$customerController = new Adminsite\Controllers\CustomerController();

$id = $_GET['id'];
$data = $customerController->edit($id)->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $nama = $_POST['nama'];
  $no_hp = $_POST['no_hp'];
  $no_ktp = $_POST['no_ktp'];
  $alamat = $_POST['alamat'];

  $customerController->update($id, $_POST);

  header("Location: index_customers.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Customer</title>
  <!-- CSS -->
  <link rel="stylesheet" href="/Rolis/assets/css/style.css">
  <link rel="stylesheet" href="/Rolis/assets/css/customers.css">
  <!-- JS -->
  <script src="/Rolis/assets/js/script.js" defer></script>
</head>

<body>
  <?php include "../includes/header.php"; ?>
  <?php include "../includes/sidebar.php"; ?>

  <main class="form-wrapper">
    <h1>✏ Edit Customer</h1>
    <form method="POST" class="form-container">
      <label for="nama">Nama :</label>
      <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

      <label for="no_hp">No HP :</label>
      <input type="text" name="no_hp" id="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>" required>

      <label for="no_ktp">No KTP :</label>
      <input type="text" name="no_ktp" id="no_ktp" value="<?= htmlspecialchars($data['no_ktp']) ?>" required>

      <label for="alamat">Alamat :</label>
      <textarea name="alamat" id="alamat" required><?= htmlspecialchars($data['alamat']) ?></textarea>

      <div class="form-actions">
        <button type="submit" class="btn-update">Update</button>
        <a href="index_customers.php" class="btn-red">Kembali</a>
      </div>
    </form>
  </main>

  <?php include "../includes/footer.php"; ?>
</body>

</html>