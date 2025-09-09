<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ../index.php");
  exit;
}
?>

<?php include "includes/header.php"; ?>
<?php include "includes/sidebar.php"; ?>

<main>
  <h1>Selamat datang, <?= htmlspecialchars($_SESSION['nama_admin']) ?> 👋</h1>
  <p>Ini adalah halaman dashboard admin Rolis.</p>
  <a href="logout_admin.php">Logout</a>
</main>


<?php include "includes/footer.php"; ?>