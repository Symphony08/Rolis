<?php

namespace Adminsite\Controllers;

require_once 'Controller.php';

use DateTime;

class TransactionController extends Controller
{


    public function create($post)
    {
        $pelanggan_id = $post['pelanggan_id'];
        $produk_id = $post['produk_id'];
        $nomor_mesin = $post['nomor_mesin'];
        $nomor_body = $post['nomor_body'];
        $tanggal_garansi = $post['tanggal_garansi'];
        $tanggal_transaksi = $post['tanggal_transaksi'];

        // WIP
        // $jatuh_tempo = new DateTime($tanggal_transaksi)->modify('+30 days');

        $warna = $post['warna'];
        // Database insertion logic here
        $stmt = $this->conn->prepare("INSERT INTO transaksi (pelanggan_id, produk_id, nomor_mesin, nomor_body, tanggal_garansi, tanggal_transaksi, warna) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $pelanggan_id, $produk_id, $nomor_mesin, $nomor_body, $tanggal_garansi, $tanggal_transaksi, $warna);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        //CRUL WHATSAPP API INTEGRATION
        // Send WhatsApp message after transaction creation
        $token_query = $this->conn->query("SELECT token, send_wa FROM wa_api LIMIT 1");
        if (!$token_query || $token_query->num_rows == 0) {
            $_SESSION['flash_message'] = "Transaksi berhasil ditambahkan, tetapi token WA tidak ditemukan.";
            return $affectedRows;
        }
        $row = $token_query->fetch_assoc();
        $token = $row['token'];
        $send_wa = $row['send_wa'] ?? 0;

        if (!$send_wa) {
            $_SESSION['flash_message'] = "Transaksi berhasil ditambahkan.";
            return $affectedRows;
        }

        // Fetch customer phone and name
        $customer_query = $this->conn->query("SELECT no_hp, nama FROM pelanggan WHERE id_pelanggan = $pelanggan_id");
        $customer = $customer_query->fetch_assoc();
        $phone = $customer['no_hp'];
        $nama_pelanggan = $customer['nama'];

        // Fetch product name
        $produk_query = $this->conn->query("SELECT nama, harga FROM produk WHERE id_produk = $produk_id");
        $produk = $produk_query->fetch_assoc();
        $produk_nama = $produk['nama'];
        $produk_harga = number_format($produk['harga'], 0, ',', '.');

        // Array bulan dalam bahasa Indonesia
        $bulan_indonesia = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Format tanggal transaksi dan garansi
        $bulan_transaksi = date("m", strtotime($post['tanggal_transaksi']));
        $tanggal_transaksi = date("d", strtotime($post['tanggal_transaksi'])) . " " . $bulan_indonesia[$bulan_transaksi] . " " . date("Y", strtotime($post['tanggal_transaksi']));

        $bulan_garansi = date("m", strtotime($post['tanggal_garansi']));
        $tanggal_garansi = date("d", strtotime($post['tanggal_garansi'])) . " " . $bulan_indonesia[$bulan_garansi] . " " . date("Y", strtotime($post['tanggal_garansi']));

        // Buat pesan WhatsApp
        $message = "*ROLIS - Roda Listrik*
Jl. KH. Samanhudi No.42, Sungai Pinang Dalam, Kec. Sungai Pinang, Kota Samarinda, Kalimantan Timur 75117, Indonesia

Yth. $nama_pelanggan,

Terima kasih telah melakukan transaksi dengan kami.

Detail Transaksi:
- Produk: $produk_nama
- Harga Produk: $produk_harga
- Tanggal Transaksi: $tanggal_transaksi
- Garansi Berakhir: $tanggal_garansi

Spesifikasi:
- Warna: " . $post['warna'] . "
- Nomor Mesin: " . $post['nomor_mesin'] . "
- Nomor Body: " . $post['nomor_body'] . "

Jika ada pertanyaan, silakan hubungi kami.

Terima Kasih";

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
            $_SESSION['flash_message'] = "Transaksi berhasil ditambahkan dan pesan WhatsApp berhasil dikirim.";
        } else {
            $error_msg = $curl_error ? $curl_error : "Respons API tidak valid.";
            $_SESSION['flash_message'] = "Transaksi berhasil ditambahkan, tetapi pengiriman pesan WhatsApp gagal: " . $error_msg;
        }

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
        $tanggal_transaksi = $post['tanggal_transaksi'];
        $warna = $post['warna'];
        // Database update logic here
        $stmt = $this->conn->prepare("UPDATE transaksi SET pelanggan_id = ?, produk_id = ?, nomor_mesin = ?, nomor_body = ?, tanggal_garansi = ?, tanggal_transaksi = ?, warna = ? WHERE id_transaksi = ?");
        $stmt->bind_param("iisssssi", $pelanggan_id, $produk_id, $nomor_mesin, $nomor_body, $tanggal_garansi, $tanggal_transaksi, $warna, $id);
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
