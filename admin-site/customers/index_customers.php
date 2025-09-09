<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Site</title>
    <link rel="stylesheet" href="/Rolis/assets/css/customers.css">
</head>
<body>
    <div class="form-container">
    <h2>Tambah Customer</h2>
    <form action="proses_tambah_customers.php" method="POST">
      <div class="form-group">
        <label for="id_pelanggan">ID Pelanggan</label>
        <input type="text" id="id_pelanggan" name="id_pelanggan" required>
      </div>

      <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required>
      </div>

      <div class="form-group">
        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" required>
      </div>

      <div class="form-group">
        <label for="no_hp">No. HP</label>
        <input type="text" id="no_hp" name="no_hp" required>
      </div>

      <div class="form-group">
        <label for="nik">NIK</label>
        <input type="text" id="nik" name="nik" required>
      </div>

      <button type="submit" class="btn-submit">Tambah Data</button>
    </form>
  </div>
</body>
</html>