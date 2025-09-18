<?php

namespace Adminsite\Controllers;

require_once 'Controller.php';
// memakai use Exception agar bisa menggunakan class Exception
use Exception;

class ServiceController extends Controller
{
    public function create($post)
    {
        $pelanggan_id = strip_tags($post['pelanggan_id']);
        $produk_id = strip_tags($post['produk_id']);
        $transaksi_id = isset($post['transaksi_id']) && !empty($post['transaksi_id']) ? strip_tags($post['transaksi_id']) : null;
        $keluhan = strip_tags($post['keluhan']);

        $stmt = $this->conn->prepare("INSERT INTO servis (pelanggan_id, produk_id, transaksi_id, keluhan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $pelanggan_id, $produk_id, $transaksi_id, $keluhan);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM servis WHERE id_servis = ?");
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }

    public function update($id, $post)
    {
        $pelanggan_id = strip_tags($post['pelanggan_id']);
        $produk_id = strip_tags($post['produk_id']);
        $transaksi_id = isset($post['transaksi_id']) && !empty($post['transaksi_id']) ? strip_tags($post['transaksi_id']) : null;
        $keluhan = strip_tags($post['keluhan']);

        $stmt = $this->conn->prepare("UPDATE servis SET pelanggan_id = ?, produk_id = ?, transaksi_id = ?, keluhan = ? WHERE id_servis = ?");
        $stmt->bind_param("iiisi", $pelanggan_id, $produk_id, $transaksi_id, $keluhan, $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows;
    }

    public function show()
    {
        return $this->select("SELECT s.*, p.nama as pelanggan_nama, pr.nama as produk_nama, pr.jenis as produk_jenis, m.value as merek_nama, t.nomor_mesin, t.nomor_body FROM servis s LEFT JOIN pelanggan p ON s.pelanggan_id = p.id_pelanggan LEFT JOIN produk pr ON s.produk_id = pr.id_produk LEFT JOIN merek m ON pr.merek_id = m.id_merek LEFT JOIN transaksi t ON s.transaksi_id = t.id_transaksi ORDER BY s.id_servis DESC");
    }

    public function edit($id)
    {
        return $this->select("SELECT s.*, p.nama as pelanggan_nama, pr.nama as produk_nama, pr.jenis as produk_jenis, m.value as merek_nama, t.nomor_mesin, t.nomor_body FROM servis s LEFT JOIN pelanggan p ON s.pelanggan_id = p.id_pelanggan LEFT JOIN produk pr ON s.produk_id = pr.id_produk LEFT JOIN merek m ON pr.merek_id = m.id_merek LEFT JOIN transaksi t ON s.transaksi_id = t.id_transaksi WHERE s.id_servis = $id");
    }

    public function getPelanggan()
    {
        return $this->select("SELECT id_pelanggan, nama FROM pelanggan ORDER BY nama ASC");
    }

    public function getProduk()
    {
        return $this->select("SELECT p.id_produk, p.nama, p.jenis, m.value as merek FROM produk p LEFT JOIN merek m ON p.merek_id = m.id_merek ORDER BY p.nama ASC");
    }

    public function getTransaksi()
    {
        return $this->select("SELECT t.id_transaksi, t.nomor_mesin, p.nama as pelanggan_nama, t.pelanggan_id, t.produk_id FROM transaksi t LEFT JOIN pelanggan p ON t.pelanggan_id = p.id_pelanggan ORDER BY t.id_transaksi DESC");
    }
}
