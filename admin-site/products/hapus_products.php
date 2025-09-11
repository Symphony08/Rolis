<?php
session_start();
include "../includes/db.php";
require_once "../controllers/ProductController.php";
$productController = new Adminsite\Controllers\ProductController();

$id = $_GET['id'];
$productController->delete($id);

$_SESSION['flash_message'] = 'Produk berhasil dihapus.';
header("Location: index_products.php");
exit;
