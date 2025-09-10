<?php

namespace Adminsite\Controllers;

require_once 'Controller.php';

class ProductController extends Controller
{
    private $uploadDir = '../../assets/img/';
    private $allowedFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];


    private function handleFileUpload($file)
    {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $timestamp = date('YmdHis');
            $baseName = implode('.', array_slice($fileNameCmps, 0, -1));
            // Revert to original filename with timestamp appended
            $newFileName = $baseName . '_' . $timestamp . '.' . $fileExtension;

            if (in_array($fileExtension, $this->allowedFileExtensions)) {
                $dest_path = $this->uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    return $dest_path;
                }
            }
        }
        return null;
    }

    public function create($post, $file)
    {
        $id_merek = $post['id_merek'];
        $nama = strip_tags($post['nama']);
        $jenis = strip_tags($post['jenis']);
        $deskripsi = strip_tags($post['deskripsi']);
        $warna = strip_tags($post['warna']);
        $harga = strip_tags((int)$post['harga']);
        $foto = $this->handleFileUpload($file);

        // Corrected SQL query matching the table schema
        $stmt = $this->conn->prepare("INSERT INTO produk (merek_id, nama, jenis, deskripsi, warna, harga, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssis", $id_merek, $nama, $jenis, $deskripsi, $warna, $harga, $foto);

        if ($stmt->execute()) {
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            return $affectedRows;
        } else {
            // Handle error (e.g., log or return false)
            $stmt->close();
            return false;
        }
    }

    public function edit($id, $post, $file)
    {
        $id_merek = $post['id_merek'];
        $nama = strip_tags($post['nama']);
        $jenis = strip_tags($post['jenis']);
        $deskripsi = strip_tags($post['deskripsi']);
        $warna = strip_tags($post['warna']);
        $harga = strip_tags($post['harga']);
        $foto = $this->handleFileUpload($file);

        if ($foto) {
            // Update with new photo
            $stmt = $this->conn->prepare("UPDATE produk SET merek_id = ?, nama = ?, jenis = ?, deskripsi = ?, warna = ?, harga = ?, foto = ? WHERE id_produk = ?");
            $stmt->bind_param("issssssi", $id_merek, $nama, $jenis, $deskripsi, $warna, $harga, $foto, $id);
        } else {
            // Update without changing the photo
            $stmt = $this->conn->prepare("UPDATE produk SET merek_id = ?, nama = ?, jenis = ?, deskripsi = ?, warna = ?, harga = ? WHERE id_produk = ?");
            $stmt->bind_param("isssssi", $id_merek, $nama, $jenis, $deskripsi, $warna, $harga, $id);
        }

        if ($stmt->execute()) {
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            return $affectedRows;
        } else {
            // Handle error (e.g., log or return false)
            $stmt->close();
            return false;
        }
    }


    public function delete($id)
    {
        // Database deletion logic here
        $stmt = $this->conn->prepare("DELETE FROM produk WHERE id_produk = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }
}
