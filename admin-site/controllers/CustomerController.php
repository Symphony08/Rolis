<?php

namespace Adminsite\Controllers;
// memakai use Exception agar bisa menggunakan class Exception
use Exception;

class CustomerController extends Controller
{
    // konstruktor dihapus karena sudah ada di class Controller yang di-extend
    // jadi tidak perlu mendefinisikan ulang koneksi database di sini
    // method create, delete, dan edit untuk mengelola data pelanggan

    public function create($post)
    {
        $nama = strip_tags($post['nama']);
        $alamat = strip_tags($post['alamat']);
        $no_hp = strip_tags($post['no_hp']);
        $no_ktp = strip_tags($post['no_ktp']);
        // Database insertion logic here

        $stmt = $this->conn->prepare("INSERT INTO pelanggan (nama, alamat, no_hp, no_ktp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $alamat, $no_hp, $no_ktp);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows;
    }

    public function delete($id)
    {
        // Database deletion logic here
        $stmt = $this->conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?");
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

    public function edit($id, $post)
    {
        $nama = strip_tags($post['nama']);
        $alamat = strip_tags($post['alamat']);
        $no_hp = strip_tags($post['no_hp']);
        $no_ktp = strip_tags($post['no_ktp']);
        // Database update logic here

        $stmt = $this->conn->prepare("UPDATE pelanggan SET nama = ?, alamat = ?, no_hp = ?, no_ktp = ? WHERE id_pelanggan = ?");
        $stmt->bind_param("ssssi", $nama, $alamat, $no_hp, $no_ktp, $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows;
    }
}
