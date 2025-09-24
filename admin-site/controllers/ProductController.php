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
                    return $dest_path; // hanya simpan filename, bukan path penuh
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
        $harga = (int)$post['harga'];
        $foto = $this->handleFileUpload($file);

        if ($foto === null) {
            return false; // wajib upload foto saat create
        }

        $stmt = $this->conn->prepare("INSERT INTO produk (merek_id, nama, jenis, deskripsi, harga, foto) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssis", $id_merek, $nama, $jenis, $deskripsi, $harga, $foto);

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
        $harga = (int)$post['harga'];

        // cek file upload
        $foto = $this->handleFileUpload($file);
        if ($foto) {
            // Get old foto and delete it
            $stmt_old = $this->conn->prepare("SELECT foto FROM produk WHERE id_produk=?");
            $stmt_old->bind_param("i", $id);
            $stmt_old->execute();
            $result_old = $stmt_old->get_result();
            if ($result_old->num_rows > 0) {
                $row_old = $result_old->fetch_assoc();
                $old_foto = $row_old['foto'];
                if ($old_foto && file_exists($old_foto)) {
                    unlink($old_foto);
                }
            }
            $stmt_old->close();
            // update dengan foto baru
            $stmt = $this->conn->prepare("UPDATE produk SET merek_id=?, nama=?, jenis=?, deskripsi=?, harga=?, foto=? WHERE id_produk=?");
            $stmt->bind_param("isssssi", $id_merek, $nama, $jenis, $deskripsi, $harga, $foto, $id);
        } else {
            // pakai foto lama
            $stmt = $this->conn->prepare("UPDATE produk SET merek_id=?, nama=?, jenis=?, deskripsi=?, harga=? WHERE id_produk=?");
            $stmt->bind_param("issssi", $id_merek, $nama, $jenis, $deskripsi, $harga, $id);
        }

        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function delete($id)
    {
        // Get the foto path before deleting
        $stmt_select = $this->conn->prepare("SELECT foto FROM produk WHERE id_produk=?");
        $stmt_select->bind_param("i", $id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $foto = $row['foto'];
            if ($foto && file_exists($foto)) {
                unlink($foto);
            }
        }
        $stmt_select->close();
        // Now delete from DB
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
