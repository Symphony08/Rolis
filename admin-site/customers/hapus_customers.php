<?php
session_start();
require_once "../includes/db.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan = $id");

header("Location: index_customers.php");
exit;
?>
