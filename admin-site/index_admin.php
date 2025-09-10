<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ../index.php");
  exit;
}
?>

<?php include "includes/header.php"; ?>
<?php include "includes/sidebar.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Admin</title>
  <style>
    main {
      margin-top: 60px;
      padding: 20px;
      text-align: center;
    }
    .welcome-text {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }
    .welcome-sub {
      font-size: 1.2rem;
      color: #555;
    }
  </style>
</head>
<body>
  <main>
    <h1 class="welcome-text">Selamat datang, Admin👋</h1>
    <p class="welcome-sub">Ini adalah halaman dashboard admin Rolis.</p>
  </main>
</body>
</html>


<?php include "includes/footer.php"; ?>