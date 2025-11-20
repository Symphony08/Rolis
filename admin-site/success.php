<?php
session_start();

// Redirect jika tidak ada pesan sukses
if (!isset($_SESSION['success_message'])) {
  header("Location: index_user.php");
  exit;
}

$success_message = $_SESSION['success_message'];
unset($_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Berhasil - ROLIS</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts - Poppins -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="icon" href="/Rolis/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../public/assets/css/user_success.css">
</head>

<body>
  <div class="success-container">
    <div class="success-icon">
      <i class="bi bi-check-circle-fill success-checkmark"></i>
    </div>

    <h1>Registrasi Berhasil!</h1>
    <p class="success-message">
      <?= htmlspecialchars($success_message) ?>
    </p>

    <div class="info-box">
      <div class="info-item">
        <i class="bi bi-shield-check"></i>
        <span>Data Anda telah tersimpan dengan aman</span>
      </div>
      <div class="info-item">
        <i class="bi bi-person-check"></i>
        <span>Tim kami akan segera memproses pendaftaran Anda</span>
      </div>
      <div class="info-item">
        <i class="bi bi-telephone"></i>
        <span>Kami akan menghubungi Anda melalui WhatsApp</span>
      </div>
    </div>

    <a href="index_user.php" class="btn-home">
      <i class="bi bi-house-fill me-2"></i>Kembali ke Beranda
    </a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>