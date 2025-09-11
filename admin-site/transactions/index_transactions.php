<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<main class="container mt-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>💳 Transactions</h1>
        <a href="tambah_transactions.php" class="btn btn-primary">➕ Tambah Transaction</a>
    </div>

    <div class="alert alert-info">
        <p>Transactions management coming soon...</p>
    </div>
</main>

<?php include "../includes/footer.php"; ?>
