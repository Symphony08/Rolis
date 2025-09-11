<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
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

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h1 class="card-title mb-0">✏ Edit Customer</h1>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="no_hp" class="form-label">No HP</label>
              <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?= htmlspecialchars($data['no_hp']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="no_ktp" class="form-label">No KTP</label>
              <input type="text" name="no_ktp" id="no_ktp" class="form-control" value="<?= htmlspecialchars($data['no_ktp']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="alamat" class="form-label">Alamat</label>
              <textarea name="alamat" id="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data['alamat']) ?></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Update</button>
              <a href="index_customers.php" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
