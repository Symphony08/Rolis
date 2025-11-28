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
        $biaya = isset($post['biaya']) && !empty($post['biaya']) ? $post['biaya'] : null;
        $keterangan = isset($post['keterangan']) && !empty($post['keterangan']) ? strip_tags($post['keterangan']) : null;

        // Cek apakah user input manual produk
        $isManual = isset($post['merek_manual']) && !empty($post['merek_manual']);

        if ($isManual) {
            $jenis_produk = strip_tags($post['jenis_manual']);
            $merek_input = strip_tags($post['merek_manual']);
            $model_input = strip_tags($post['model_manual']);
            $warna_input = strip_tags($post['warna_manual']);

            // Insert atau ambil ID merek
            $stmt_merek = $this->conn->prepare("SELECT id_merek FROM merek WHERE value = ? LIMIT 1");
            $stmt_merek->bind_param("s", $merek_input);
            $stmt_merek->execute();
            $result_merek = $stmt_merek->get_result();
            
            if ($result_merek->num_rows > 0) {
                $merek_id = $result_merek->fetch_assoc()['id_merek'];
            } else {
                $stmt_insert_merek = $this->conn->prepare("INSERT INTO merek (value) VALUES (?)");
                $stmt_insert_merek->bind_param("s", $merek_input);
                $stmt_insert_merek->execute();
                $merek_id = $this->conn->insert_id;
                $stmt_insert_merek->close();
            }
            $stmt_merek->close();

            // Insert atau ambil ID model
            $stmt_model = $this->conn->prepare("SELECT id_model FROM model WHERE value = ? LIMIT 1");
            $stmt_model->bind_param("s", $model_input);
            $stmt_model->execute();
            $result_model = $stmt_model->get_result();
            
            if ($result_model->num_rows > 0) {
                $model_id = $result_model->fetch_assoc()['id_model'];
            } else {
                $stmt_insert_model = $this->conn->prepare("INSERT INTO model (value) VALUES (?)");
                $stmt_insert_model->bind_param("s", $model_input);
                $stmt_insert_model->execute();
                $model_id = $this->conn->insert_id;
                $stmt_insert_model->close();
            }
            $stmt_model->close();

            // Insert atau ambil ID warna
            $stmt_warna = $this->conn->prepare("SELECT id_warna FROM warna WHERE value = ? LIMIT 1");
            $stmt_warna->bind_param("s", $warna_input);
            $stmt_warna->execute();
            $result_warna = $stmt_warna->get_result();
            
            if ($result_warna->num_rows > 0) {
                $warna_id = $result_warna->fetch_assoc()['id_warna'];
            } else {
                $stmt_insert_warna = $this->conn->prepare("INSERT INTO warna (value) VALUES (?)");
                $stmt_insert_warna->bind_param("s", $warna_input);
                $stmt_insert_warna->execute();
                $warna_id = $this->conn->insert_id;
                $stmt_insert_warna->close();
            }
            $stmt_warna->close();

            // Buat produk baru dengan data yang diinput
            $deskripsi = "Produk dari servis manual";
            $harga = 0;
            $foto = "";
            
            $stmt_produk = $this->conn->prepare("INSERT INTO produk (merek_id, model_id, warna_id, jenis, deskripsi, harga, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_produk->bind_param("iiissis", $merek_id, $model_id, $warna_id, $jenis_produk, $deskripsi, $harga, $foto);
            $stmt_produk->execute();
            $produk_id = $this->conn->insert_id;
            $stmt_produk->close();

            $merek_produk = null;
            $model_produk = null;
            $warna_produk = null;
        } else {
            $produk_id = strip_tags($post['produk_id']);
            $jenis_produk = null;
            $merek_produk = null;
            $model_produk = null;
            $warna_produk = null;
        }

        $status = "PROGRESS";

        $stmt = $this->conn->prepare(
            "INSERT INTO servis (pelanggan_id, produk_id, transaksi_id, keluhan, biaya, keterangan, jenis_produk, merek_produk, model_produk, warna_produk, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        // pelanggan_id(i), produk_id(i), transaksi_id(i), keluhan(s), biaya(i), keterangan(s), jenis_produk(s), merek_produk(s), model_produk(s), warna_produk(s), status(s)
        $stmt->bind_param("iiissssssss", $pelanggan_id, $produk_id, $transaksi_id, $keluhan, $biaya, $keterangan, $jenis_produk, $merek_produk, $model_produk, $warna_produk, $status);

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new Exception("Execute failed: " . $error);
        }

        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        if ($affectedRows > 0) {

            // Send WhatsApp message for new service request
            // Fetch WhatsApp API token and send_wa setting
            $token_query = $this->conn->query("SELECT token, send_wa FROM wa_api LIMIT 1");
            if (!$token_query || $token_query->num_rows == 0) {
                $_SESSION['flash_message'] = "Servis berhasil ditambahkan, tetapi token WA tidak ditemukan.";
                return $affectedRows;
            }
            $row = $token_query->fetch_assoc();
            $token = $row['token'];
            $send_wa = $row['send_wa'] ?? 0;

            if (!$send_wa) {
                $_SESSION['flash_message'] = "Servis berhasil ditambahkan.";
                return $affectedRows;
            }

            // Fetch customer phone and name
            $customer_query = $this->conn->query("SELECT no_hp, nama FROM pelanggan WHERE id_pelanggan = $pelanggan_id");
            if (!$customer_query || $customer_query->num_rows == 0) {
                $_SESSION['flash_message'] = "Servis berhasil ditambahkan, tetapi data pelanggan tidak ditemukan.";
                return $affectedRows;
            }
            $customer = $customer_query->fetch_assoc();
            $phone = $customer['no_hp'];
            $nama_pelanggan = $customer['nama'];

            // Determine product display name
            if ($merek_produk !== null && $merek_produk !== '') {
                $produk_display = $merek_produk . ' - ' . $model_produk . ' - ' . $warna_produk;
            } else {
                $prod_query = $this->conn->query("SELECT p.*, m.value AS merek, mo.value AS model, w.value AS warna FROM produk p LEFT JOIN merek m ON p.merek_id = m.id_merek LEFT JOIN model mo ON p.model_id = mo.id_model LEFT JOIN warna w ON p.warna_id = w.id_warna WHERE p.id_produk = $produk_id");
                if (!$prod_query || $prod_query->num_rows == 0) {
                    $produk_display = 'Produk';
                } else {
                    $prod = $prod_query->fetch_assoc();
                    $produk_display = ($prod['merek'] ?? '-') . ' - ' . ($prod['model'] ?? '-') . ' - ' . ($prod['warna'] ?? '-');
                }
            }

            // Compose WhatsApp message
            $message = "*ROLIS - Roda Listrik*\n"
                . "Jl. KH. Samanhudi No.42, Sungai Pinang Dalam, Kec. Sungai Pinang, Kota Samarinda, Kalimantan Timur 75117, Indonesia\n\n"
                . "Yth. $nama_pelanggan,\n\n"
                . "Permintaan servis Anda untuk produk: $produk_display telah diterima.\n"
                . "Status: $status\n"
                . "Keluhan: $keluhan\n\n"
                . "Kami akan segera memproses servis Anda.\n\n"
                . "Terima kasih telah mempercayakan servis Anda kepada kami.\n\n"
                . "Jika ada pertanyaan, silakan hubungi kami.\n\n"
                . "Terima Kasih";

            // Send WhatsApp message via cURL
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $phone . '&message=' . urlencode($message),
            ]);
            $response = curl_exec($curl);
            $data = json_decode($response, TRUE);
            $curl_error = curl_error($curl);
            curl_close($curl);

            if ($data && isset($data['result']) && $data['result'] === 'true') {
                $_SESSION['flash_message'] = "Servis berhasil ditambahkan dan pesan WhatsApp berhasil dikirim.";
            } else {
                $error_msg = $curl_error ? $curl_error : "Respons API tidak valid.";
                $_SESSION['flash_message'] = "Servis berhasil ditambahkan, tetapi pengiriman pesan WhatsApp gagal: " . $error_msg;
            }
        }

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
        $biaya = isset($post['biaya']) && !empty($post['biaya']) ? $post['biaya'] : null;
        $keterangan = isset($post['keterangan']) && !empty($post['keterangan']) ? strip_tags($post['keterangan']) : null;
        $status = strip_tags($post['status']);

        // Cek apakah user input manual produk
        $isManual = isset($post['merek_manual']) && !empty($post['merek_manual']);

        if ($isManual) {
            $jenis_produk = strip_tags($post['jenis_manual']);
            $merek_input = strip_tags($post['merek_manual']);
            $model_input = strip_tags($post['model_manual']);
            $warna_input = strip_tags($post['warna_manual']);

            // Insert atau ambil ID merek
            $stmt_merek = $this->conn->prepare("SELECT id_merek FROM merek WHERE value = ? LIMIT 1");
            $stmt_merek->bind_param("s", $merek_input);
            $stmt_merek->execute();
            $result_merek = $stmt_merek->get_result();
            
            if ($result_merek->num_rows > 0) {
                $merek_id = $result_merek->fetch_assoc()['id_merek'];
            } else {
                $stmt_insert_merek = $this->conn->prepare("INSERT INTO merek (value) VALUES (?)");
                $stmt_insert_merek->bind_param("s", $merek_input);
                $stmt_insert_merek->execute();
                $merek_id = $this->conn->insert_id;
                $stmt_insert_merek->close();
            }
            $stmt_merek->close();

            // Insert atau ambil ID model
            $stmt_model = $this->conn->prepare("SELECT id_model FROM model WHERE value = ? LIMIT 1");
            $stmt_model->bind_param("s", $model_input);
            $stmt_model->execute();
            $result_model = $stmt_model->get_result();
            
            if ($result_model->num_rows > 0) {
                $model_id = $result_model->fetch_assoc()['id_model'];
            } else {
                $stmt_insert_model = $this->conn->prepare("INSERT INTO model (value) VALUES (?)");
                $stmt_insert_model->bind_param("s", $model_input);
                $stmt_insert_model->execute();
                $model_id = $this->conn->insert_id;
                $stmt_insert_model->close();
            }
            $stmt_model->close();

            // Insert atau ambil ID warna
            $stmt_warna = $this->conn->prepare("SELECT id_warna FROM warna WHERE value = ? LIMIT 1");
            $stmt_warna->bind_param("s", $warna_input);
            $stmt_warna->execute();
            $result_warna = $stmt_warna->get_result();
            
            if ($result_warna->num_rows > 0) {
                $warna_id = $result_warna->fetch_assoc()['id_warna'];
            } else {
                $stmt_insert_warna = $this->conn->prepare("INSERT INTO warna (value) VALUES (?)");
                $stmt_insert_warna->bind_param("s", $warna_input);
                $stmt_insert_warna->execute();
                $warna_id = $this->conn->insert_id;
                $stmt_insert_warna->close();
            }
            $stmt_warna->close();

            // Buat produk baru dengan data yang diinput
            $deskripsi = "Produk dari servis manual";
            $harga = 0;
            $foto = "";
            
            $stmt_produk = $this->conn->prepare("INSERT INTO produk (merek_id, model_id, warna_id, jenis, deskripsi, harga, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_produk->bind_param("iiissis", $merek_id, $model_id, $warna_id, $jenis_produk, $deskripsi, $harga, $foto);
            $stmt_produk->execute();
            $produk_id = $this->conn->insert_id;
            $stmt_produk->close();

            $merek_produk = null;
            $model_produk = null;
            $warna_produk = null;
        } else {
            $produk_id = strip_tags($post['produk_id']);
            $jenis_produk = null;
            $merek_produk = null;
            $model_produk = null;
            $warna_produk = null;
        }

        $stmt = $this->conn->prepare(
            "UPDATE servis SET pelanggan_id = ?, produk_id = ?, transaksi_id = ?, keluhan = ?, biaya = ?, keterangan = ?, jenis_produk = ?, merek_produk = ?, model_produk = ?, warna_produk = ?, status = ? WHERE id_servis = ?"
        );
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        // Bind parameters
        $stmt->bind_param("iiisissssssi", $pelanggan_id, $produk_id, $transaksi_id, $keluhan, $biaya, $keterangan, $jenis_produk, $merek_produk, $model_produk, $warna_produk, $status, $id);

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new Exception("Execute failed: " . $error);
        }

        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        // Send WhatsApp message if status is "DONE"
        if (strtoupper($status) === "DONE") {
            // Fetch WhatsApp API token and send_wa setting
            $token_query = $this->conn->query("SELECT token, send_wa FROM wa_api LIMIT 1");
            if (!$token_query || $token_query->num_rows == 0) {
                $_SESSION['flash_message'] = "Servis berhasil diperbarui, tetapi token WA tidak ditemukan.";
                return $affectedRows;
            }
            $row = $token_query->fetch_assoc();
            $token = $row['token'];
            $send_wa = $row['send_wa'] ?? 0;

            if (!$send_wa) {
                $_SESSION['flash_message'] = "Servis berhasil diperbarui.";
                return $affectedRows;
            }

            // Fetch customer phone and name
            $customer_query = $this->conn->query("SELECT no_hp, nama FROM pelanggan WHERE id_pelanggan = $pelanggan_id");
            $customer = $customer_query->fetch_assoc();
            $phone = $customer['no_hp'];
            $nama_pelanggan = $customer['nama'];

            // Determine product display name
            if ($merek_produk !== null && $merek_produk !== '') {
                $produk_display = $merek_produk . ' - ' . $model_produk . ' - ' . $warna_produk;
            } else {
                $prod_query = $this->conn->query("SELECT p.*, m.value AS merek, mo.value AS model, w.value AS warna FROM produk p LEFT JOIN merek m ON p.merek_id = m.id_merek LEFT JOIN model mo ON p.model_id = mo.id_model LEFT JOIN warna w ON p.warna_id = w.id_warna WHERE p.id_produk = $produk_id");
                if ($prod_query && $prod_query->num_rows > 0) {
                    $prod = $prod_query->fetch_assoc();
                    $produk_display = ($prod['merek'] ?? '-') . ' - ' . ($prod['model'] ?? '-') . ' - ' . ($prod['warna'] ?? '-');
                } else {
                    $produk_display = 'Produk';
                }
            }

            // Compose WhatsApp message
            $message = "*ROLIS - Roda Listrik*\n"
                . "Jl. KH. Samanhudi No.42, Sungai Pinang Dalam, Kec. Sungai Pinang, Kota Samarinda, Kalimantan Timur 75117, Indonesia\n\n"
                . "Yth. $nama_pelanggan,\n\n"
                . "Servis Anda untuk produk: $produk_display telah selesai.\n"
                . "Status: $status\n"
                . "Keluhan: $keluhan\n\n"
                . "Terima kasih telah mempercayakan servis Anda kepada kami.\n\n"
                . "Jika ada pertanyaan, silakan hubungi kami.\n\n"
                . "Terima Kasih";

            // Send WhatsApp message via cURL
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $phone . '&message=' . urlencode($message),
            ]);
            $response = curl_exec($curl);
            $data = json_decode($response, TRUE);
            $curl_error = curl_error($curl);
            curl_close($curl);

            if ($data && isset($data['result']) && $data['result'] === 'true') {
                $_SESSION['flash_message'] = "Servis berhasil diperbarui dan pesan WhatsApp berhasil dikirim.";
            } else {
                $error_msg = $curl_error ? $curl_error : "Respons API tidak valid.";
                $_SESSION['flash_message'] = "Servis berhasil diperbarui, tetapi pengiriman pesan WhatsApp gagal: " . $error_msg;
            }
        }

        return $affectedRows;
    }

    public function show()
    {
        return $this->select("SELECT s.*, 
            p.nama as pelanggan_nama, 
            t.nomor_mesin,
            CASE
                WHEN s.jenis_produk IS NOT NULL AND s.jenis_produk != '' THEN s.jenis_produk
                ELSE pr.jenis
            END as jenis_display,
            CASE
                WHEN s.merek_produk IS NOT NULL AND s.merek_produk != '' THEN s.merek_produk
                ELSE m.value
            END as merek_display,
            CASE
                WHEN s.model_produk IS NOT NULL AND s.model_produk != '' THEN s.model_produk
                ELSE mo.value
            END as model_display,
            CASE
                WHEN s.warna_produk IS NOT NULL AND s.warna_produk != '' THEN s.warna_produk
                ELSE w.value
            END as warna_display
        FROM servis s 
        LEFT JOIN pelanggan p ON s.pelanggan_id = p.id_pelanggan 
        LEFT JOIN produk pr ON s.produk_id = pr.id_produk 
        LEFT JOIN merek m ON pr.merek_id = m.id_merek 
        LEFT JOIN model mo ON pr.model_id = mo.id_model
        LEFT JOIN warna w ON pr.warna_id = w.id_warna
        LEFT JOIN transaksi t ON s.transaksi_id = t.id_transaksi 
        ORDER BY s.id_servis DESC");
    }

    public function edit($id)
    {
        return $this->select("SELECT s.*, 
            p.nama as pelanggan_nama, 
            pr.jenis as produk_jenis, 
            m.value as merek_nama,
            mo.value as model_nama,
            w.value as warna_nama,
            t.nomor_mesin, 
            t.nomor_body 
        FROM servis s 
        LEFT JOIN pelanggan p ON s.pelanggan_id = p.id_pelanggan 
        LEFT JOIN produk pr ON s.produk_id = pr.id_produk 
        LEFT JOIN merek m ON pr.merek_id = m.id_merek 
        LEFT JOIN model mo ON pr.model_id = mo.id_model
        LEFT JOIN warna w ON pr.warna_id = w.id_warna
        LEFT JOIN transaksi t ON s.transaksi_id = t.id_transaksi 
        WHERE s.id_servis = $id");
    }

    public function getPelanggan()
    {
        return $this->select("SELECT id_pelanggan, nama FROM pelanggan ORDER BY nama ASC");
    }

    public function getProduk()
    {
        return $this->select("SELECT p.id_produk, p.jenis, m.value as merek, mo.value as model, w.value as warna 
            FROM produk p 
            LEFT JOIN merek m ON p.merek_id = m.id_merek 
            LEFT JOIN model mo ON p.model_id = mo.id_model 
            LEFT JOIN warna w ON p.warna_id = w.id_warna 
            ORDER BY m.value ASC, mo.value ASC");
    }

    public function getTransaksi()
    {
        return $this->select("SELECT t.id_transaksi, t.nomor_mesin, p.nama as pelanggan_nama, t.pelanggan_id, t.produk_id, 
            CONCAT(m.value, ' - ', mo.value, ' - ', w.value) as produk_nama, t.tanggal_transaksi 
            FROM transaksi t 
            LEFT JOIN pelanggan p ON t.pelanggan_id = p.id_pelanggan 
            LEFT JOIN produk pr ON t.produk_id = pr.id_produk 
            LEFT JOIN merek m ON pr.merek_id = m.id_merek 
            LEFT JOIN model mo ON pr.model_id = mo.id_model 
            LEFT JOIN warna w ON pr.warna_id = w.id_warna 
            ORDER BY t.id_transaksi DESC");
    }
}