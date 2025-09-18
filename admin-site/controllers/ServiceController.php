<?php

namespace Adminsite\Controllers;

require_once 'Controller.php';
class ServiceController extends Controller
{
    public function create($post) {
        $pelanggan_id = $post['pelanggan_id'];
        $produk_id = $post['produk_id'];
        $transaksi_id = $post['transaksi_id'];
        $keluhan = $post['keluhan'];

        $stmt = $this->conn->prepare("INSERT INTO servis (pelanggan_id, produk_id, transaksi_id, keluhan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $pelanggan_id, $produk_id, $transaksi_id, $keluhan);
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;

    }
}