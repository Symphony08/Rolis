<?php
session_start();
include "../includes/db.php";
require_once "../controllers/ProductController.php";
$productController = new Adminsite\Controllers\ProductController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];
    if (is_array($ids)) {
        foreach ($ids as $id) {
            $productController->delete($id);
        }
        // Set flash message in session for AJAX response
        $_SESSION['flash_message'] = 'Produk berhasil dihapus.';
        echo json_encode(['success' => true, 'message' => $_SESSION['flash_message']]);
        exit;
    } else {
        $_SESSION['flash_message'] = 'Invalid request.';
        echo json_encode(['success' => false, 'message' => $_SESSION['flash_message']]);
        exit;
    }
    // Removed unreachable header redirect here
}

$id = $_GET['id'] ?? null;
if ($id !== null) {
    $productController->delete($id);
    $_SESSION['flash_message'] = 'Produk berhasil dihapus.';
    header("Location: index_products.php");
    exit;
}
