<?php
session_start();
require_once "../includes/db.php";
require_once "../controllers/CustomerController.php";
$customerController = new Adminsite\Controllers\CustomerController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];
    if (is_array($ids)) {
        foreach ($ids as $id) {
            $customerController->delete($id);
        }
        $_SESSION['flash_message'] = 'Customer berhasil dihapus.';
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
    $customerController->delete($id);
    $_SESSION['flash_message'] = 'Customer berhasil dihapus.';
    header("Location: index_customers.php");
    exit;
}
