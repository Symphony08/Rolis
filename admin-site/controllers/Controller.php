<?php

namespace Adminsite\Controllers;

require_once '../includes/db.php';

// class controller untuk koneksi database dan digunakan oleh controller lain
// seperti ProductController, CustomerController, TransactionController
// agar tidak mengulangi kode koneksi database di setiap controller
// menggunakan namespace agar tidak terjadi konflik nama class jika ada class lain dengan
// nama yang sama di project lain

// also ni aku pake buat rapi aja sih biar keliatan professional lah
abstract class Controller
{
    protected $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function select($query)
    {
        $result = $this->conn->query($query);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
