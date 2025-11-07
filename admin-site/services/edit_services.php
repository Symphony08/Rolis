<?php
session_start();
require_once "../includes/db.php";
require_once "../controllers/ServiceController.php";

use Adminsite\Controllers\ServiceController;

$serviceController = new ServiceController();

$id = $_GET['id'];
$data = $serviceController->edit($id)->fetch_assoc();

$pelangganList = $serviceController->getPelanggan();
$produkList = $serviceController->getProduk();
$transaksiList = $serviceController->getTransaksi();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    $serviceController->update($id, $_POST);
  } catch (Exception $e) {
    $_SESSION['flash_message'] = 'Error updating service: ' . $e->getMessage();
  }

  header("Location: index_services.php");
  exit;
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<main class="container mt-5 pt-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-6">
      <div class="card rounded-4 shadow-sm p-4">
        <div class="mb-4">
          <h3 class="fw-bold">✏ Edit Servis</h3>
          <p class="text-muted">Perbarui informasi servis yang terdaftar.</p>
        </div>
        <form method="POST" novalidate>
          <!-- ===== Transaksi ===== -->
          <div class="mb-3 row align-items-center">
            <label for="transaksi_id" class="col-sm-4 col-form-label fw-semibold">Transaksi (Opsional)</label>
            <div class="col-sm-8">
              <input type="text" id="selectedTransaksi" class="form-control rounded-3" placeholder="Pilih transaksi" readonly style="cursor: pointer;" value="<?php if ($data['transaksi_id']) {
                                                                                                                                                                foreach ($transaksiList as $t) {
                                                                                                                                                                  if ($t['id_transaksi'] == $data['transaksi_id']) {
                                                                                                                                                                    echo htmlspecialchars($t['nomor_mesin'] . ' - ' . $t['pelanggan_nama']);
                                                                                                                                                                    break;
                                                                                                                                                                  }
                                                                                                                                                                }
                                                                                                                                                              } ?>">
              <input type="hidden" name="transaksi_id" id="transaksi_id" value="<?= $data['transaksi_id'] ?? '' ?>">
            </div>
          </div>
          <!-- ===== Status ===== -->
          <div class="mb-3 row align-items-center">
            <label for="status" class="col-sm-4 col-form-label fw-semibold">Status Servis</label>
            <div class="col-sm-8">
              <select name="status" id="status" class="form-select rounded-3" required>
                <option value="">Pilih Status</option>
                <option value="PROGRESS" <?= ($data['status'] ?? '') == 'PROGRESS' ? 'selected' : '' ?>>Proses</option>
                <option value="DONE" <?= ($data['status'] ?? '') == 'DONE' ? 'selected' : '' ?>>Selesai</option>
              </select>
              <div class="invalid-feedback">Status wajib dipilih.</div>
            </div>
          </div>
          <!-- ===== Pelanggan ===== -->
          <div class="mb-3 row align-items-center">
            <label for="pelanggan_id" class="col-sm-4 col-form-label fw-semibold">Pelanggan</label>
            <div class="col-sm-8">
              <select name="pelanggan_id" id="pelanggan_id" class="form-select rounded-3" required>
                <option value="">Pilih Pelanggan</option>
                <?php foreach ($pelangganList as $pelanggan): ?>
                  <option value="<?= $pelanggan['id_pelanggan'] ?>" <?= $pelanggan['id_pelanggan'] == $data['pelanggan_id'] ? 'selected' : '' ?>><?= htmlspecialchars($pelanggan['nama']) ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Pelanggan wajib dipilih.</div>
            </div>
          </div>
          <!-- ===== Produk ===== -->
          <div class="mb-3 row align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Produk</label>
            <div class="col-sm-8">
              <!-- Toggle switch -->
              <div class="d-flex align-items-center mb-2">
                <span class="me-2">Pilih</span>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="produkToggleSwitch">
                </div>
                <span class="ms-2">Input Manual</span>
              </div>
              <!-- Dropdown produk -->
              <select name="produk_id" id="produk_id" class="form-select rounded-3" required>
                <option value="">Pilih Produk</option>
                <?php foreach ($produkList as $produk): ?>
                  <option value="<?= $produk['id_produk'] ?>" <?= $produk['id_produk'] == $data['produk_id'] ? 'selected' : '' ?>><?= htmlspecialchars($produk['nama']) ?> (<?= htmlspecialchars($produk['jenis']) ?> - <?= htmlspecialchars($produk['merek']) ?>)</option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Produk wajib dipilih.</div>
              <!-- Input manual produk -->
              <div id="manualProdukFields" class="d-none mt-3">
                <div class="mb-2">
                  <input type="text" name="nama_manual" class="form-control" placeholder="Nama Produk" value="<?= htmlspecialchars($data['nama_manual'] ?? '') ?>">
                </div>
                <div class="mb-2">
                  <select name="jenis_manual" class="form-select">
                    <option value="">Pilih Jenis</option>
                    <option value="MOTOR" <?= ($data['jenis_manual'] ?? '') == 'MOTOR' ? 'selected' : '' ?>>Motor</option>
                    <option value="SEPEDA" <?= ($data['jenis_manual'] ?? '') == 'SEPEDA' ? 'selected' : '' ?>>Sepeda</option>
                  </select>
                </div>
                <div class="mb-2">
                  <input type="text" name="merek_manual" class="form-control" placeholder="Merek" value="<?= htmlspecialchars($data['merek_manual'] ?? '') ?>">
                </div>
                <div class="mb-2">
                  <input type="text" name="warna_manual" class="form-control" placeholder="Warna" value="<?= htmlspecialchars($data['warna_manual'] ?? '') ?>">
                </div>
              </div>
            </div>
          </div>
          <!-- ===== Keluhan ===== -->
          <div class="mb-3 row align-items-center">
            <label for="keluhan" class="col-sm-4 col-form-label fw-semibold">Keluhan</label>
            <div class="col-sm-8">
              <textarea name="keluhan" id="keluhan" class="form-control rounded-3" rows="3" required><?= htmlspecialchars($data['keluhan']) ?></textarea>
              <div class="invalid-feedback">Keluhan wajib diisi.</div>
            </div>
          </div>
          <!-- ===== Biaya ===== -->
          <div class="mb-3 row align-items-center">
            <label for="biaya" class="col-sm-4 col-form-label fw-semibold">Biaya</label>
            <div class="col-sm-8">
              <input type="number" name="biaya" id="biaya" class="form-control rounded-3" step="0.01" min="0" value="<?= htmlspecialchars($data['biaya'] ?? '') ?>">
            </div>
          </div>
          <!-- ===== Keterangan ===== -->
          <div class="mb-3 row align-items-center">
            <label for="keterangan" class="col-sm-4 col-form-label fw-semibold">Keterangan</label>
            <div class="col-sm-8">
              <textarea name="keterangan" id="keterangan" class="form-control rounded-3" rows="3"><?= htmlspecialchars($data['keterangan'] ?? '') ?></textarea>
            </div>
          </div>
          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 flex-grow-1">Update Servis</button>
            <a href="index_services.php" class="btn btn-danger rounded-3 px-4 py-2 flex-grow-1">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<!-- Transaksi Modal -->
<div class="modal fade" id="transaksiModal" tabindex="-1" aria-labelledby="transaksiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transaksiModalLabel">Pilih Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="transaksiTable" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nomor Mesin</th>
                <th class="text-center">Pelanggan</th>
                <th class="text-center">Produk</th>
                <th class="text-center">Tanggal Transaksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($transaksiList as $transaksi):
              ?>
                <tr class="transaksi-row" style="cursor: pointer;" data-id="<?= $transaksi['id_transaksi'] ?>" data-nomor-mesin="<?= htmlspecialchars($transaksi['nomor_mesin']) ?>" data-pelanggan-id="<?= $transaksi['pelanggan_id'] ?>" data-pelanggan-nama="<?= htmlspecialchars($transaksi['pelanggan_nama']) ?>" data-produk-id="<?= $transaksi['produk_id'] ?>" data-produk-nama="<?= htmlspecialchars($transaksi['produk_nama']) ?>">
                  <td class="text-center"><?= $no++ ?></td>
                  <td class="text-center"><?= htmlspecialchars($transaksi['nomor_mesin']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($transaksi['pelanggan_nama']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($transaksi['produk_nama']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($transaksi['tanggal_transaksi']) ?></td>
                </tr>
              <?php endforeach; ?>
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

    // Initialize Select2 for produk select
    $('#produk_id').select2({
      placeholder: "Cari dan pilih produk",
      allowClear: true,
      width: '100%'
    });

    // Initialize Select2 for status select
    $('#status').select2({
      placeholder: "Pilih status servis",
      allowClear: false,
      width: '100%'
    });

    // Open transaksi modal on input click
    $('#selectedTransaksi').on('click', function() {
      $('#transaksiModal').modal('show');
    });

    // Initialize DataTable for transaksi modal
    $('#transaksiTable').DataTable({
      "pageLength": 5,
      "lengthMenu": [
        [5, 10, 15, 25, -1],
        [5, 10, 15, 25, "Semua"]
      ],
      "order": [
        [4, 'desc']
      ],
      "columnDefs": [{
        "orderable": false,
        "targets": [0]
      }],
      dom: 'frtip',
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

    // Handle transaksi selection
    $(document).on('click', '.transaksi-row', function() {
      var transaksiId = $(this).data('id');
      var nomorMesin = $(this).data('nomor-mesin');
      var pelangganId = $(this).data('pelanggan-id');
      var pelangganNama = $(this).data('pelanggan-nama');
      var produkId = $(this).data('produk-id');
      var produkNama = $(this).data('produk-nama');
      $('#transaksi_id').val(transaksiId);
      $('#selectedTransaksi').val(nomorMesin + ' - ' + pelangganNama);
      $('#transaksiModal').modal('hide');

      // Set pelanggan and produk
      $('#pelanggan_id').val(pelangganId).trigger('change');
      $('#produk_id').val(produkId);

      // Disable pelanggan and produk selects
      $('#pelanggan_id').prop('disabled', true);
      $('#produk_id').prop('disabled', true);

      // Hide and disable toggle switch and its labels
      $('#produkToggleSwitch').hide();
      $('#produkToggleSwitch').prop('disabled', true);
      $('#produkToggleSwitch').parent().prev().hide();
      $('#produkToggleSwitch').parent().next().hide();

      // Ensure produk is in select mode
      $('#produkToggleSwitch').prop('checked', false);
      toggleProdukMode();
      $('#produk_id').prop('disabled', true);
      // Clear manual input values
      $('#manualProdukFields input, #manualProdukFields select').val('');
    });

    // Function to toggle produk input mode
    function toggleProdukMode() {
      if ($('#produkToggleSwitch').is(':checked')) {
        // Input Manual mode
        $('#produk_id').addClass('d-none');
        $('#manualProdukFields').removeClass('d-none');
        $('#produk_id').removeAttr('required');
        // Add required to manual inputs
        $('#manualProdukFields input, #manualProdukFields select').attr('required', 'required');
      } else {
        // Pilih mode
        $('#produk_id').removeClass('d-none');
        $('#manualProdukFields').addClass('d-none');
        $('#produk_id').attr('required', 'required');
        // Remove required from manual inputs
        $('#manualProdukFields input, #manualProdukFields select').removeAttr('required');
        // Clear manual input values
        $('#manualProdukFields input, #manualProdukFields select').val('');
      }
    }

    // Initial toggle
    toggleProdukMode();

    // Event listener for toggle switch
    $('#produkToggleSwitch').on('change', toggleProdukMode);

    // Clear transaksi selection
    $('#selectedTransaksi').on('input', function() {
      if ($(this).val() === '') {
        $('#transaksi_id').val('');
        // Enable pelanggan and produk selects
        $('#pelanggan_id').prop('disabled', false);
        $('#produk_id').prop('disabled', false);

        // Show and enable toggle switch and its labels
        $('#produkToggleSwitch').show();
        $('#produkToggleSwitch').prop('disabled', false);
        $('#produkToggleSwitch').parent().prev().show();
        $('#produkToggleSwitch').parent().next().show();

        // Clear pelanggan and produk selects
        $('#pelanggan_id').val('').trigger('change');
        $('#produk_id').val('');
      }
    });

    $('form').on('submit', function() {
      if ($('#transaksi_id').val()) {
        // Re-enable selects before submit so their values are included in POST
        $('#pelanggan_id').prop('disabled', false);
        $('#produk_id').prop('disabled', false);
      }
    });
  });
</script>

<?php include "../includes/footer.php"; ?>