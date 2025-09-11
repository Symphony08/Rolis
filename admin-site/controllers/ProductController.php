<?php

namespace Adminsite\Controllers;

require_once 'Controller.php';

class ProductController extends Controller
{
    private $uploadDir = '../../uploads/';
    private $allowedFileExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

private function handleFileUpload($file)
{
    if (is_array($file) && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $timestamp = date('YmdHis');
        $baseName = implode('.', array_slice($fileNameCmps, 0, -1));
        $newFileName = $baseName . '_' . $timestamp . '.' . $fileExtension;

        if (in_array($fileExtension, $this->allowedFileExtensions)) {
            $dest_path = $this->uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // ⬅ simpan hanya nama file
                return $newFileName;
            }
        }
    }
    return null;
}


    public function create($post, $file)
    {
        $id_merek = $post['merek_id'];
        $nama = strip_tags($post['nama']);
        $jenis = strip_tags($post['jenis']);
        $deskripsi = strip_tags($post['deskripsi']);
        $warna = strip_tags($post['warna']);
        $harga = (int)$post['harga'];
        $foto = $this->handleFileUpload($file);

        if ($foto === null) {
            return false; // wajib upload foto saat create
        }

        $stmt = $this->conn->prepare("INSERT INTO produk (merek_id, nama, jenis, deskripsi, warna, harga, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssis", $id_merek, $nama, $jenis, $deskripsi, $warna, $harga, $foto);

        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function update($id, $post, $file)
    {
        $id_merek = $post['merek_id'];
        $nama = strip_tags($post['nama']);
        $jenis = strip_tags($post['jenis']);
        $deskripsi = strip_tags($post['deskripsi']);
        $warna = strip_tags($post['warna']);
        $harga = (int)$post['harga'];

        // cek file upload
        $foto = $this->handleFileUpload($file);
        if ($foto) {
            // update dengan foto baru
            $stmt = $this->conn->prepare("UPDATE produk SET merek_id=?, nama=?, jenis=?, deskripsi=?, warna=?, harga=?, foto=? WHERE id_produk=?");
            $stmt->bind_param("issssssi", $id_merek, $nama, $jenis, $deskripsi, $warna, $harga, $foto, $id);
        } else {
            // pakai foto lama
            $stmt = $this->conn->prepare("UPDATE produk SET merek_id=?, nama=?, jenis=?, deskripsi=?, warna=?, harga=? WHERE id_produk=?");
            $stmt->bind_param("isssssi", $id_merek, $nama, $jenis, $deskripsi, $warna, $harga, $id);
        }

        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM produk WHERE id_produk=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function edit($id)
    {
        return $this->select("SELECT * FROM produk WHERE id_produk = $id");
    }
}
