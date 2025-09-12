<?php
session_start();
include "../includes/db.php";
require_once "../controllers/TransactionController.php";
$transactionController = new Adminsite\Controllers\TransactionController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
  $ids = $_POST['ids'];
  if (is_array($ids)) {
    foreach ($ids as $id) {
      $transactionController->delete($id);
    }
    $_SESSION['flash_message'] = 'Transaksi berhasil dihapus.';
    echo json_encode(['success' => true, 'message' => $_SESSION['flash_message']]);
    exit;
  } else {
    $_SESSION['flash_message'] = 'Invalid request.';
    echo json_encode(['success' => false, 'message' => $_SESSION['flash_message']]);
    exit;
  }
}

$id = $_GET['id'] ?? null;
if ($id !== null) {
  $transactionController->delete($id);
  $_SESSION['flash_message'] = 'Transaksi berhasil dihapus.';
  header("Location: index_transactions.php");
  exit;
}
