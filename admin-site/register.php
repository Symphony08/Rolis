<?php
session_start();
require_once "includes/db.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = trim(strip_tags($_POST['nama']));
  $alamat = trim(strip_tags($_POST['alamat']));
  $no_hp = trim(strip_tags($_POST['no_hp']));
  $email = trim(strip_tags($_POST['email']));
  $merek_motor = trim(strip_tags($_POST['merek_motor']));
  $model_motor = trim(strip_tags($_POST['model_motor']));
  $warna_motor = trim(strip_tags($_POST['warna_motor']));
  $tgl_beli = $_POST['tgl_beli'];
  $keterangan = trim(strip_tags($_POST['keterangan']));

  // Validasi email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = 'Format email tidak valid!';
    header("Location: register.php");
    exit;
  }

  try {
    $conn->begin_transaction();

    // Insert atau ambil ID merek
    $stmt = $conn->prepare("SELECT id_merek FROM merek WHERE LOWER(value) = LOWER(?)");
    $stmt->bind_param("s", $merek_motor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $merek_id = $result->fetch_assoc()['id_merek'];
    } else {
      $stmt_insert = $conn->prepare("INSERT INTO merek (value) VALUES (?)");
      $stmt_insert->bind_param("s", $merek_motor);
      $stmt_insert->execute();
      $merek_id = $conn->insert_id;
      $stmt_insert->close();
    }
    $stmt->close();

    // Insert atau ambil ID model
    $stmt = $conn->prepare("SELECT id_model FROM model WHERE LOWER(value) = LOWER(?)");
    $stmt->bind_param("s", $model_motor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $model_id = $result->fetch_assoc()['id_model'];
    } else {
      $stmt_insert = $conn->prepare("INSERT INTO model (value) VALUES (?)");
      $stmt_insert->bind_param("s", $model_motor);
      $stmt_insert->execute();
      $model_id = $conn->insert_id;
      $stmt_insert->close();
    }
    $stmt->close();

    // Insert atau ambil ID warna
    $stmt = $conn->prepare("SELECT id_warna FROM warna WHERE LOWER(value) = LOWER(?)");
    $stmt->bind_param("s", $warna_motor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $warna_id = $result->fetch_assoc()['id_warna'];
    } else {
      $stmt_insert = $conn->prepare("INSERT INTO warna (value) VALUES (?)");
      $stmt_insert->bind_param("s", $warna_motor);
      $stmt_insert->execute();
      $warna_id = $conn->insert_id;
      $stmt_insert->close();
    }
    $stmt->close();

    // Insert pelanggan
    $stmt = $conn->prepare("INSERT INTO pelanggan (nama, alamat, no_hp, email, tgl_beli, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama, $alamat, $no_hp, $email, $tgl_beli, $keterangan);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    $_SESSION['success_message'] = 'Registrasi berhasil! Terima kasih telah mendaftar.';
    header("Location: success.php");
    exit;

  } catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
    header("Location: register.php");
    exit;
  }
}

// Ambil data untuk autocomplete
$merek_list = [];
$model_list = [];
$warna_list = [];

$result_merek = $conn->query("SELECT DISTINCT value FROM merek ORDER BY value ASC");
if ($result_merek) {
  while ($row = $result_merek->fetch_assoc()) {
    $merek_list[] = $row['value'];
  }
}

$result_model = $conn->query("SELECT DISTINCT value FROM model ORDER BY value ASC");
if ($result_model) {
  while ($row = $result_model->fetch_assoc()) {
    $model_list[] = $row['value'];
  }
}

$result_warna = $conn->query("SELECT DISTINCT value FROM warna ORDER BY value ASC");
if ($result_warna) {
  while ($row = $result_warna->fetch_assoc()) {
    $warna_list[] = $row['value'];
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - ROLIS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="icon" href="/Rolis/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../public/assets/css/user_register.css">
</head>

<body>
  <div class="register-container">
    <div class="register-card">
      <div class="card-header">
        <img src="../public/android-chrome-512x512.png" alt="ROLIS Logo" class="header-logo">
        <h2>Form Registrasi</h2>
        <p>Lengkapi data Anda untuk mendaftar</p>
      </div>

      <div class="card-body">
        <?php if (isset($_SESSION['error_message'])): ?>
          <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
          </div>
          <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="POST" id="registrationForm" novalidate>
          <!-- Data Pribadi -->
          <h5 class="section-title">Data Pribadi</h5>

          <div class="form-group">
            <label for="nama">Nama Lengkap <span class="required">*</span></label>
            <input type="text" class="form-control" id="nama" name="nama" required>
            <div class="invalid-feedback">Nama lengkap wajib diisi</div>
          </div>

          <div class="form-group">
            <label for="alamat">Alamat <span class="required">*</span></label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
            <div class="invalid-feedback">Alamat wajib diisi</div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="no_hp">No. HP (WhatsApp) <span class="required">*</span></label>
                <input type="tel" class="form-control" id="no_hp" name="no_hp" required>
                <div class="invalid-feedback">No. HP wajib diisi</div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Email wajib diisi dengan format yang benar</div>
              </div>
            </div>
          </div>

          <!-- Data Motor -->
          <h5 class="section-title">Data Motor</h5>

          <div class="form-group">
            <label for="merek_motor">Merek Motor <span class="required">*</span></label>
            <input type="text" class="form-control" id="merek_motor" name="merek_motor" required>
            <div class="invalid-feedback">Merek motor wajib diisi</div>
          </div>

          <div class="form-group">
            <label for="model_motor">Tipe Motor <span class="required">*</span></label>
            <input type="text" class="form-control" id="model_motor" name="model_motor" required>
            <div class="invalid-feedback">Tipe/model motor wajib diisi</div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="warna_motor">Warna Motor <span class="required">*</span></label>
                <input type="text" class="form-control" id="warna_motor" name="warna_motor" required>
                <div class="invalid-feedback">Warna motor wajib diisi</div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="tgl_beli">Tanggal Beli Motor <span class="required">*</span></label>
                <input type="date" class="form-control" id="tgl_beli" name="tgl_beli" required>
                <div class="invalid-feedback">Tanggal beli wajib diisi</div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="keterangan">Keterangan <span class="required">*</span></label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required placeholder="Tambahkan keterangan atau catatan untuk kami"></textarea>
            <small class="form-text">Jika tidak ada keterangan silahkan ketik tanda "-"</small>
            <div class="invalid-feedback">Keterangan wajib diisi</div>
          </div>

          <div class="button-group">
            <button type="submit" class="btn btn-submit">Daftar Sekarang</button>
            <a href="index_user.php" class="btn btn-back">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const merekList = <?= json_encode($merek_list) ?>;
    const modelList = <?= json_encode($model_list) ?>;
    const warnaList = <?= json_encode($warna_list) ?>;

    $("#merek_motor").autocomplete({
      source: merekList,
      minLength: 0
    }).focus(function() {
      $(this).autocomplete("search", "");
    });

    $("#model_motor").autocomplete({
      source: modelList,
      minLength: 0
    }).focus(function() {
      $(this).autocomplete("search", "");
    });

    $("#warna_motor").autocomplete({
      source: warnaList,
      minLength: 0
    }).focus(function() {
      $(this).autocomplete("search", "");
    });

    (function() {
      'use strict';
      const form = document.getElementById('registrationForm');

      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        const emailInput = document.getElementById('email');
        if (emailInput.value && !emailInput.value.includes('@')) {
          emailInput.setCustomValidity('Email harus mengandung simbol @');
        } else {
          emailInput.setCustomValidity('');
        }

        form.classList.add('was-validated');
      }, false);

      document.getElementById('email').addEventListener('input', function() {
        if (this.value && !this.value.includes('@')) {
          this.setCustomValidity('Email harus mengandung simbol @');
        } else {
          this.setCustomValidity('');
        }
      });
    })();

    document.getElementById('tgl_beli').max = new Date().toISOString().split('T')[0];
  </script>
</body>

</html>