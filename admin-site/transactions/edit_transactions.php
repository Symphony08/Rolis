<?php
session_start();
include "../includes/db.php";
require_once "../controllers/TransactionController.php";

$transactionController = new Adminsite\Controllers\TransactionController();

$id = $_GET['id'];
$data = $transactionController->select("SELECT * FROM transaksi WHERE id_transaksi = $id")->fetch_assoc();

$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama ASC");
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama ASC");

// Ambil nama produk untuk pre-populate
$produk_nama = $transactionController->select("SELECT nama FROM produk WHERE id_produk = " . $data['produk_id'])->fetch_assoc()['nama'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $transactionController->edit($id, $_POST);
  $_SESSION['flash_message'] = "Transaksi berhasil diperbarui!";
  header("Location: index_transactions.php");
  exit;
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">Edit Transaksi</h3>
          <p class="text-muted">Perbarui informasi transaksi.</p>
        </div>
        <form method="POST" novalidate>
          <div class="mb-3 row align-items-center">
            <label for="pelanggan_id" class="col-sm-4 col-form-label fw-semibold">Pelanggan</label>
            <div class="col-sm-8">
              <select name="pelanggan_id" id="pelanggan_id" class="form-select rounded-3" required>
                <option value="" selected disabled>Pilih pelanggan</option>
                <?php while ($c = mysqli_fetch_assoc($pelanggan)): ?>
                  <option value="<?= $c['id_pelanggan'] ?>" <?= $data['pelanggan_id'] == $c['id_pelanggan'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nama']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
              <div class="invalid-feedback">Pelanggan wajib dipilih.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="produk_id" class="col-sm-4 col-form-label fw-semibold">Produk</label>
            <div class="col-sm-8">
              <div class="input-group">
                <input type="text" id="selectedProduk" class="form-control rounded-3" placeholder="Pilih produk" value="<?= htmlspecialchars($produk_nama) ?>" readonly>
                <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-toggle="modal" data-bs-target="#produkModal">Pilih</button>
              </div>
              <input type="hidden" name="produk_id" id="produk_id" value="<?= $data['produk_id'] ?>" required>
              <div class="invalid-feedback">Produk wajib dipilih.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="warna" class="col-sm-4 col-form-label fw-semibold">Warna</label>
            <div class="col-sm-8">
              <input type="text" name="warna" id="warna" value="<?= $data['warna'] ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Warna wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="nomor_mesin" class="col-sm-4 col-form-label fw-semibold">Nomor Mesin</label>
            <div class="col-sm-8">
              <input type="text" name="nomor_mesin" id="nomor_mesin" value="<?= htmlspecialchars($data['nomor_mesin']) ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Nomor mesin wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="nomor_body" class="col-sm-4 col-form-label fw-semibold">Nomor Body</label>
            <div class="col-sm-8">
              <input type="text" name="nomor_body" id="nomor_body" value="<?= htmlspecialchars($data['nomor_body']) ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Nomor body wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="tanggal_transaksi" class="col-sm-4 col-form-label fw-semibold">Tanggal Transaksi</label>
            <div class="col-sm-8">
              <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" value="<?= $data['tanggal_transaksi'] ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Tanggal transaksi wajib diisi.</div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="tanggal_garansi" class="col-sm-4 col-form-label fw-semibold">Tanggal Berakhir Garansi</label>
            <div class="col-sm-8">
              <input type="date" name="tanggal_garansi" id="tanggal_garansi" value="<?= $data['tanggal_garansi'] ?>" class="form-control rounded-3" required>
              <div class="invalid-feedback">Tanggal berakhir garansi wajib diisi.</div>
            </div>
          </div>

          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Update Transaksi</button>
            <a href="index_transactions.php" class="btn btn-danger rounded-3 px-4 py-2 flex-grow-1">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<!-- Produk Modal -->
<div class="modal fade" id="produkModal" tabindex="-1" aria-labelledby="produkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="produkModalLabel">Pilih Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="produkTable" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Merek</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Jenis</th>
                <th class="text-center">Deskripsi</th>
                <th class="text-center">Harga</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $produk_result = mysqli_query($conn, "SELECT p.*, m.value AS nama_merek FROM produk p JOIN merek m ON p.merek_id = m.id_merek ORDER BY p.id_produk DESC");
              if ($produk_result && mysqli_num_rows($produk_result) > 0):
                $no = 1;
                while ($row = mysqli_fetch_assoc($produk_result)):
              ?>
                <tr class="produk-row" style="cursor: pointer;" data-id="<?= $row['id_produk'] ?>" data-nama="<?= htmlspecialchars($row['nama']) ?>">
                  <td class="text-center"><?= $no++ ?></td>
                  <td class="text-center"><?= htmlspecialchars($row['nama_merek']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($row['nama']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($row['jenis']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($row['deskripsi']) ?></td>
                  <td class="text-center">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                </tr>
              <?php endwhile; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Initialize Select2 for pelanggan select
  $('#pelanggan_id').select2({
    placeholder: "Cari dan pilih pelanggan",
    allowClear: true,
    width: '100%'
  });

  // Initialize DataTable for produk modal
  $('#produkTable').DataTable({
    "pageLength": 5,
    "lengthMenu": [[5, 10, 15, 25, -1], [5, 10, 15, 25, "Semua"]],
    "order": [[1, 'asc']],
    "columnDefs": [{
      "orderable": false,
      "targets": [0, 5]
    }],
    dom: 'rtip',
    language: {
      "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
      "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
      "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
      "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
      "sInfoPostFix": "",
      "sInfoThousands": ".",
      "sLengthMenu": "Tampilkan _MENU_ entri",
      "sLoadingRecords": "Sedang memuat...",
      "sProcessing": "Sedang memproses...",
      "sSearch": "Cari:",
      "sZeroRecords": "Tidak ditemukan data yang sesuai",
      "oPaginate": {
        "sFirst": "Pertama",
        "sLast": "Terakhir",
        "sNext": "Selanjutnya",
        "sPrevious": "Sebelumnya"
      },
      "oAria": {
        "sSortAscending": ": aktifkan untuk mengurutkan kolom ke atas",
        "sSortDescending": ": aktifkan untuk mengurutkan kolom ke bawah"
      }
    }
  });

  // Handle produk selection
  $(document).on('click', '.produk-row', function() {
    var produkId = $(this).data('id');
    var produkNama = $(this).data('nama');
    $('#produk_id').val(produkId);
    $('#selectedProduk').val(produkNama);
    $('#produkModal').modal('hide');
  });
});
</script>

<?php include "../includes/footer.php"; ?>
