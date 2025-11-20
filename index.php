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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link rel="icon" href="public/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="public/assets/css/login_admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <img src="public/logo.png" alt="Logo">
      <h2>Selamat Datang Admin</h2>
    </div>

    <div class="login-form">
      <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
      <?php endif; ?>
      <form method="POST">
        <div class="input-group">
          <i class="fa fa-user"></i>
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
          <i class="fa fa-lock"></i>
          <input type="password" name="password" placeholder="Masukkan password Anda" required>
        </div>
        <button type="submit" class="login-btn">
          <i class="fa fa-sign-in-alt"></i> Masuk
        </button>
      </form>
    </div>
  </div>
</body>
</html>
