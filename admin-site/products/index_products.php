<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: ../index.php");
  exit;
}
?>
<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>

<main>
  <h1>Product Column</h1>
</main>

<?php include "../includes/footer.php"; ?>
