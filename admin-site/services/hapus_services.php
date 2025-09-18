<?php
session_start();
require_once "../includes/db.php";
require_once "../controllers/ServiceController.php";

use Adminsite\Controllers\ServiceController;

$serviceController = new ServiceController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];
    if (is_array($ids)) {
        foreach ($ids as $id) {
            $serviceController->delete($id);
        }
        $_SESSION['flash_message'] = 'Servis berhasil dihapus.';
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
    $serviceController->delete($id);
    $_SESSION['flash_message'] = 'Servis berhasil dihapus.';
    header("Location: index_services.php");
    exit;
}
