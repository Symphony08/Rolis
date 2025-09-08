<?php

class CustomerController
{
    private $conn;

    public function __construct()
    {
        include 'Controller.php';
        global $conn;
        $this->conn = $conn;
    }


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
        $stmt->execute([$id]);
    }
}
