<?php
session_start();
require_once "../includes/db.php";

$id = $_GET['id'];
$stmt = mysqli_prepare($conn, "DELETE FROM produk WHERE id_produk = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: index_products.php");
exit;
?>
