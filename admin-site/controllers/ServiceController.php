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
        $transaksi_id = isset($post['transaksi_id']) && !empty($post['transaksi_id'])
            ? strip_tags($post['transaksi_id'])
            : null;
        $keluhan = strip_tags($post['keluhan']);

        // Cek apakah user input manual produk
        $isManual = isset($post['nama_manual']) && !empty($post['nama_manual']);

        if ($isManual) {
            $produk_id = null;
            $jenis_produk = strip_tags($post['jenis_manual']);
            $merek_produk = strip_tags($post['merek_manual']);
            $warna_produk = strip_tags($post['warna_manual']);

            // Use nama_manual directly as nama_produk (product name only)
            $nama_produk = strip_tags($post['nama_manual']);
        } else {
            $produk_id = strip_tags($post['produk_id']);
            $jenis_produk = null;
            $merek_produk = null;
            $warna_produk = null;
            $nama_produk = null;
        }

        $status = "PROGRESS";

        $stmt = $this->conn->prepare(
            "INSERT INTO servis (pelanggan_id, produk_id, transaksi_id, keluhan, nama_produk, jenis_produk, merek_produk, warna_produk, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("iiissssss", $pelanggan_id, $produk_id, $transaksi_id, $keluhan, $nama_produk, $jenis_produk, $merek_produk, $warna_produk, $status);
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
        $transaksi_id = isset($post['transaksi_id']) && !empty($post['transaksi_id'])
            ? strip_tags($post['transaksi_id'])
            : null;
        $keluhan = strip_tags($post['keluhan']);
        $status = strip_tags($post['status']);

        // Cek apakah user input manual produk
        $isManual = isset($post['nama_manual']) && !empty($post['nama_manual']);

        if ($isManual) {
            $produk_id = null;
            $jenis_produk = strip_tags($post['jenis_manual']);
            $merek_produk = strip_tags($post['merek_manual']);
            $warna_produk = strip_tags($post['warna_manual']);

            // Use nama_manual directly as nama_produk (product name only)
            $nama_produk = strip_tags($post['nama_manual']);
        } else {
            $produk_id = strip_tags($post['produk_id']);
            $jenis_produk = null;
            $merek_produk = null;
            $warna_produk = null;
            $nama_produk = null;
        }

        $stmt = $this->conn->prepare(
            "UPDATE servis SET pelanggan_id = ?, produk_id = ?, transaksi_id = ?, keluhan = ?, nama_produk = ?, jenis_produk = ?, merek_produk = ?, warna_produk = ?, status = ? WHERE id_servis = ?"
        );
        $stmt->bind_param("iiissssssi", $pelanggan_id, $produk_id, $transaksi_id, $keluhan, $nama_produk, $jenis_produk, $merek_produk, $warna_produk, $status, $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows;
    }

    public function show()
    {
        return $this->select("SELECT s.*, p.nama as pelanggan_nama, pr.nama as produk_nama, pr.jenis as produk_jenis, m.value as merek_nama, t.nomor_mesin, t.nomor_body, t.warna,
            CASE
                WHEN s.nama_produk IS NOT NULL AND s.nama_produk != '' THEN s.nama_produk
                ELSE pr.nama
            END as produk_display,
            CASE
                WHEN s.jenis_produk IS NOT NULL AND s.jenis_produk != '' THEN s.jenis_produk
                ELSE pr.jenis
            END as jenis_display,
            CASE
                WHEN s.merek_produk IS NOT NULL AND s.merek_produk != '' THEN s.merek_produk
                ELSE m.value
            END as merek_display,
            CASE
                WHEN s.warna_produk IS NOT NULL AND s.warna_produk != '' THEN s.warna_produk
                ELSE t.warna
            END as warna_display

            FROM servis s LEFT JOIN pelanggan p ON s.pelanggan_id = p.id_pelanggan LEFT JOIN produk pr ON s.produk_id = pr.id_produk LEFT JOIN merek m ON pr.merek_id = m.id_merek LEFT JOIN transaksi t ON s.transaksi_id = t.id_transaksi ORDER BY s.id_servis DESC");
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
