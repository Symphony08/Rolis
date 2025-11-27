<?php
session_start();
require_once __DIR__ . "/admin-site/includes/db.php";

if (isset($_SESSION['admin'])) {
    header("Location: admin-site/index_admin.php");
    exit;
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM master WHERE username = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($password === $row['password']) {
            $_SESSION['admin'] = $row['username'];
            $_SESSION['nama_admin'] = $row['nama'];
            header("Location: admin-site/index_admin.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - ROLIS</title>
  <link rel="icon" href="public/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="public/assets/css/login_admin.css">
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <div class="logo-circle">
        <img src="public/logo.png" alt="Logo ROLIS">
      </div>
      <h2>Login Admin</h2>
      <p>Selamat datang kembali!</p>
    </div>

    <div class="login-form">
      <?php if (!empty($error)): ?>
        <div class="error-message">
          <i class="fas fa-exclamation-circle"></i>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" autocomplete="off">
        <div class="input-group">
          <label for="username">Username</label>
          <div class="input-wrapper">
            <i class="fa fa-user"></i>
            <input 
              type="text" 
              id="username"
              name="username" 
              placeholder="Masukkan username Anda" 
              required 
              autofocus
            >
          </div>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i class="fa fa-lock"></i>
            <input 
              type="password" 
              id="password"
              name="password" 
              placeholder="Masukkan password Anda" 
              required
            >
          </div>
        </div>

        <button type="submit" class="login-btn">
          <i class="fa fa-sign-in-alt"></i>
          <span>Masuk</span>
        </button>
      </form>

      <div class="footer-text">
        &copy; 2025 ROLIS - Roda Listrik
      </div>
    </div>
  </div>
</body>
</html>