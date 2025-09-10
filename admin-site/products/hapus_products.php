<?php
session_start();
include "../includes/db.php";

$id = $_GET['id'];

// hapus foto juga kalau ada
$result = mysqli_query($conn, "SELECT foto FROM produk WHERE id_produk=$id");
$data = mysqli_fetch_assoc($result);
if ($data && !empty($data['foto']) && file_exists("../uploads/".$data['foto'])) {
    unlink("../uploads/".$data['foto']);
}

mysqli_query($conn, "DELETE FROM produk WHERE id_produk=$id");

header("Location: index_products.php");
exit;
