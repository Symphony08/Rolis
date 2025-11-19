<?php
session_start();
require_once "admin-site/includes/db.php";

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
    // Start transaction
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

    // Commit transaction
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
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts - Poppins -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <!-- jQuery UI for Autocomplete -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="icon" href="/Rolis/favicon.ico" type="image/x-icon">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #FFFDF2 0%, #F5F3E8 100%);
      min-height: 100vh;
      padding: 2rem 0;
    }

    .form-container {
      max-width: 700px;
      margin: 0 auto;
      animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      background: white;
    }

    .card-header {
      background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
      color: white;
      border-radius: 20px 20px 0 0 !important;
      padding: 2rem;
      text-align: center;
    }

    .card-header h2 {
      margin: 0;
      font-weight: 700;
      font-size: 2rem;
    }

    .card-header p {
      margin: 0.5rem 0 0 0;
      opacity: 0.9;
    }

    .card-body {
      padding: 2.5rem;
    }

    .form-label {
      font-weight: 600;
      color: #2C3E50;
      margin-bottom: 0.5rem;
    }

    .required-mark {
      color: #e74c3c;
    }

    .form-control, .form-select {
      border: 2px solid #E8E8E8;
      border-radius: 10px;
      padding: 0.75rem;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: #2C3E50;
      box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.1);
    }

    .input-group-text {
      background: #F8F9FA;
      border: 2px solid #E8E8E8;
      border-right: none;
      border-radius: 10px 0 0 10px;
    }

    .input-group .form-control {
      border-left: none;
      border-radius: 0 10px 10px 0;
    }

    .btn-submit {
      background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
      color: white;
      padding: 0.9rem 2rem;
      font-size: 1.1rem;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      width: 100%;
      transition: all 0.3s ease;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(44, 62, 80, 0.3);
    }

    .btn-back {
      background: #6C757D;
      color: white;
      padding: 0.9rem 2rem;
      font-size: 1.1rem;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      width: 100%;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .btn-back:hover {
      background: #5A6268;
      color: white;
      transform: translateY(-2px);
    }

    .section-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #2C3E50;
      margin: 2rem 0 1rem 0;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid #E8E8E8;
    }

    .alert {
      border-radius: 10px;
      border: none;
    }

    .ui-autocomplete {
      max-height: 200px;
      overflow-y: auto;
      overflow-x: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .ui-menu-item {
      padding: 0.5rem 1rem;
      cursor: pointer;
    }

    .ui-menu-item:hover {
      background: #F8F9FA;
    }

    .helper-text {
      font-size: 0.85rem;
      color: #6C757D;
      margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
      .card-body {
        padding: 1.5rem;
      }

      .card-header h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <div class="form-container">
    <div class="card">
      <div class="card-header">
        <h2><i class="bi bi-person-plus-fill me-2"></i>Form Registrasi</h2>
        <p>Lengkapi data Anda untuk mendaftar</p>
      </div>

      <div class="card-body">
        <?php if (isset($_SESSION['error_message'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="POST" id="registrationForm" novalidate>
          <!-- Data Pribadi -->
          <div class="section-title">
            <i class="bi bi-person-circle me-2"></i>Data Pribadi
          </div>

          <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap <span class="required-mark">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
              <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="invalid-feedback">Nama lengkap wajib diisi</div>
          </div>

          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat <span class="required-mark">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
              <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
            </div>
            <div class="invalid-feedback">Alamat wajib diisi</div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="no_hp" class="form-label">No. HP (WhatsApp) <span class="required-mark">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone-fill"></i></span>
                <input type="tel" class="form-control" id="no_hp" name="no_hp" required>
              </div>
              <div class="invalid-feedback">No. HP wajib diisi</div>
            </div>

            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email <span class="required-mark">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="invalid-feedback">Email wajib diisi dengan format yang benar</div>
            </div>
          </div>

          <!-- Data Motor -->
          <div class="section-title">
            <i class="bi bi-ev-front-fill me-2"></i>Data Motor
          </div>

          <div class="mb-3">
            <label for="merek_motor" class="form-label">Merek Motor <span class="required-mark">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
              <input type="text" class="form-control" id="merek_motor" name="merek_motor" required>
            </div>
            <small class="helper-text">Ketik untuk melihat saran atau masukkan merek baru</small>
            <div class="invalid-feedback">Merek motor wajib diisi</div>
          </div>

          <div class="mb-3">
            <label for="model_motor" class="form-label">Tipe/Model Motor <span class="required-mark">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-card-list"></i></span>
              <input type="text" class="form-control" id="model_motor" name="model_motor" required>
            </div>
            <small class="helper-text">Ketik untuk melihat saran atau masukkan model baru</small>
            <div class="invalid-feedback">Tipe/model motor wajib diisi</div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="warna_motor" class="form-label">Warna Motor <span class="required-mark">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-palette-fill"></i></span>
                <input type="text" class="form-control" id="warna_motor" name="warna_motor" required>
              </div>
              <small class="helper-text">Ketik untuk melihat saran atau masukkan warna baru</small>
              <div class="invalid-feedback">Warna motor wajib diisi</div>
            </div>

            <div class="col-md-6 mb-3">
              <label for="tgl_beli" class="form-label">Tanggal Beli Motor <span class="required-mark">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-fill"></i></span>
                <input type="date" class="form-control" id="tgl_beli" name="tgl_beli" required>
              </div>
              <div class="invalid-feedback">Tanggal beli wajib diisi</div>
            </div>
          </div>

          <div class="mb-4">
            <label for="keterangan" class="form-label">Keterangan <span class="required-mark">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-chat-left-text-fill"></i></span>
              <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required placeholder="Tambahkan keterangan atau catatan khusus"></textarea>
            </div>
            <div class="invalid-feedback">Keterangan wajib diisi</div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-2">
              <button type="submit" class="btn-submit">
                <i class="bi bi-check-circle-fill me-2"></i>Daftar Sekarang
              </button>
            </div>
            <div class="col-md-6">
              <a href="index_user.php" class="btn-back">
                <i class="bi bi-arrow-left me-2"></i>Kembali
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- jQuery UI -->
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Data untuk autocomplete
    const merekList = <?= json_encode($merek_list) ?>;
    const modelList = <?= json_encode($model_list) ?>;
    const warnaList = <?= json_encode($warna_list) ?>;

    // Initialize autocomplete
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

    // Form validation
    (function() {
      'use strict';
      const form = document.getElementById('registrationForm');

      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        // Custom email validation
        const emailInput = document.getElementById('email');
        if (emailInput.value && !emailInput.value.includes('@')) {
          emailInput.setCustomValidity('Email harus mengandung simbol @');
        } else {
          emailInput.setCustomValidity('');
        }

        form.classList.add('was-validated');
      }, false);

      // Real-time email validation
      document.getElementById('email').addEventListener('input', function() {
        if (this.value && !this.value.includes('@')) {
          this.setCustomValidity('Email harus mengandung simbol @');
        } else {
          this.setCustomValidity('');
        }
      });
    })();

    // Set max date for tgl_beli to today
    document.getElementById('tgl_beli').max = new Date().toISOString().split('T')[0];
  </script>
</body>

</html>