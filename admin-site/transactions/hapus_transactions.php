<?php
session_start();
include "../includes/db.php";
require_once "../controllers/TransactionController.php";

$transactionController = new Adminsite\Controllers\TransactionController();

if (isset($_GET['id'])) {
  $transactionController->delete($_GET['id']);
  $_SESSION['flash_message'] = "Transaksi berhasil dihapus!";
}

header("Location: index_transaksi.php");
exit;
