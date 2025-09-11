<?php

namespace Adminsite\Controllers;

require_once 'Controller.php';

class TransactionController extends Controller
{


    public function create($post)
    {
        $pelanggan_id = $post['pelanggan_id'];
        $produk_id = $post['produk_id'];
        $nomor_mesin = $post['nomor_mesin'];
        $nomor_body = $post['nomor_body'];
        $tanggal_garansi = $post['tanggal_garansi'];
        // Database insertion logic here
        $stmt = $this->conn->prepare("INSERT INTO transaksi (pelanggan_id, produk_id, nomor_mesin, nomor_body, tanggal_garansi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $pelanggan_id, $produk_id, $nomor_mesin, $nomor_body, $tanggal_garansi);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }

    public function delete($id)
    {
        // Database deletion logic here
        $stmt = $this->conn->prepare("DELETE FROM transaksi WHERE id_transaksi = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }

    public function edit($id, $post)
    {
        $pelanggan_id = $post['pelanggan_id'];
        $produk_id = $post['produk_id'];
        $nomor_mesin = $post['nomor_mesin'];
        $nomor_body = $post['nomor_body'];
        $tanggal_garansi = $post['tanggal_garansi'];
        // Database update logic here
        $stmt = $this->conn->prepare("UPDATE transaksi SET pelanggan_id = ?, produk_id = ?, nomor_mesin = ?, nomor_body = ?, tanggal_garansi = ? WHERE id_transaksi = ?");
        $stmt->bind_param("iisssi", $pelanggan_id, $produk_id, $nomor_mesin, $nomor_body, $tanggal_garansi, $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }

    public function show()
    {
        return $this->select("SELECT t.*, p.nama AS nama_produk, c.nama AS nama_pelanggan
    FROM transaksi t
    JOIN produk p ON t.produk_id = p.id_produk
    JOIN pelanggan c ON t.pelanggan_id = c.id_pelanggan
    ORDER BY t.id_transaksi DESC");
    }
}
