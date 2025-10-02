<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php";
require_once "../controllers/TransactionController.php";

$transactionController = new Adminsite\Controllers\TransactionController();

// Ambil data pelanggan & produk untuk dropdown
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama ASC");
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama ASC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $transactionController->create($_POST);

  // Send WhatsApp message after transaction creation
  $token_query = mysqli_query($conn, "SELECT token FROM wa_api LIMIT 1");
  $token = mysqli_fetch_assoc($token_query)['token'];
  $pelanggan_id = $_POST['pelanggan_id'];
  $produk_id = $_POST['produk_id'];

  // Fetch customer phone and name
  $customer_query = mysqli_query($conn, "SELECT no_hp, nama FROM pelanggan WHERE id_pelanggan = $pelanggan_id");
  $customer = mysqli_fetch_assoc($customer_query);
  $phone = $customer['no_hp'];
  $nama_pelanggan = $customer['nama'];

  // Fetch product name
  $produk_query = mysqli_query($conn, "SELECT nama FROM produk WHERE id_produk = $produk_id");
  $produk_nama = mysqli_fetch_assoc($produk_query)['nama'];

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
  $bulan_transaksi = date("m", strtotime($_POST['tanggal_transaksi']));
  $tanggal_transaksi = date("d", strtotime($_POST['tanggal_transaksi'])) . " " . $bulan_indonesia[$bulan_transaksi] . " " . date("Y", strtotime($_POST['tanggal_transaksi']));

  $bulan_garansi = date("m", strtotime($_POST['tanggal_garansi']));
  $tanggal_garansi = date("d", strtotime($_POST['tanggal_garansi'])) . " " . $bulan_indonesia[$bulan_garansi] . " " . date("Y", strtotime($_POST['tanggal_garansi']));

  // Buat pesan WhatsApp
  $message = "*PFSOFT - CV Paulfen Mandiri*
Jl. KH. Samanhudi No.42, Sungai Pinang Dalam, Kec. Sungai Pinang, Kota Samarinda, Kalimantan Timur 75117, Indonesia

Yth. $nama_pelanggan,

Terima kasih telah melakukan transaksi dengan kami.

Detail Transaksi:
- Produk: $produk_nama
- Tanggal Transaksi: $tanggal_transaksi
- Garansi Berakhir: $tanggal_garansi

Spesifikasi:
- Warna: " . $_POST['warna'] . "
- Nomor Mesin: " . $_POST['nomor_mesin'] . "
- Nomor Body: " . $_POST['nomor_body'] . "

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

  header("Location: index_transactions.php");
  exit;
}
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">Tambah Transaksi Baru</h3>
          <p class="text-muted">Isi informasi transaksi baru.</p>
        </div>
        <form method="POST" novalidate>
          <div class="mb-3 row align-items-center">
            <label for="pelanggan_id" class="col-sm-4 col-form-label fw-semibold">Pelanggan</label>
            <div class="col-sm-8">
              <select name="pelanggan_id" id="pelanggan_id" class="form-select rounded-3" required>
                <option value="" selected disabled>Pilih pelanggan</option>
                <?php while ($c = mysqli_fetch_assoc($pelanggan)): ?>
                  <option value="<?= $c['id_pelanggan'] ?>"><?= htmlspecialchars($c['nama']) ?></option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Pelanggan wajib dipilih.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="produk_id" class="col-sm-4 col-form-label fw-semibold">Produk</label>
            <div class="col-sm-8">
              <select name="produk_id" id="produk_id" class="form-select rounded-3" required>
                <option value="" selected disabled>Pilih produk</option>
                <?php while ($p = mysqli_fetch_assoc($produk)): ?>
                  <option value="<?= $p['id_produk'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Produk wajib dipilih.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="warna" class="col-sm-4 col-form-label fw-semibold">Warna</label>
            <div class="col-sm-8">
              <input type="text" name="warna" id="warna" class="form-control rounded-3" required>
              <div class="invalid-feedback">Warna wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="nomor_mesin" class="col-sm-4 col-form-label fw-semibold">Nomor Mesin</label>
            <div class="col-sm-8">
              <input type="text" name="nomor_mesin" id="nomor_mesin" class="form-control rounded-3" required>
              <div class="invalid-feedback">Nomor mesin wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="nomor_body" class="col-sm-4 col-form-label fw-semibold">Nomor Body</label>
            <div class="col-sm-8">
              <input type="text" name="nomor_body" id="nomor_body" class="form-control rounded-3" required>
              <div class="invalid-feedback">Nomor body wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="tanggal_transaksi" class="col-sm-4 col-form-label fw-semibold">Tanggal Transaksi</label>
            <div class="col-sm-8">
              <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control rounded-3" required>
              <div class="invalid-feedback">Tanggal transaksi wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="tanggal_garansi" class="col-sm-4 col-form-label fw-semibold">Tanggal Berakhir Garansi</label>
            <div class="col-sm-8">
              <input type="date" name="tanggal_garansi" id="tanggal_garansi" class="form-control rounded-3" required>
              <div class="invalid-feedback">Tanggal berakhir garansi wajib diisi.</div>
            </div>
          </div>

          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Tambah Transaksi</button>
            <a href="index_transactions.php" class="btn btn-danger rounded-3 px-4 py-2 flex-grow-1">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php include "../includes/footer.php"; ?>