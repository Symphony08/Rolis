<?php
session_start();
require_once "../includes/db.php";
require_once "../controllers/CustomerController.php";
$customerController = new Adminsite\Controllers\CustomerController();

$id = $_GET['id'];
$customerController->delete($id);

$_SESSION['flash_message'] = 'Customer berhasil dihapus.';
header("Location: index_customers.php");
exit;
