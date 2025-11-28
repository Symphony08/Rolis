<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
require_once "../includes/db.php";

require_once "../controllers/ServiceController.php";

use Adminsite\Controllers\ServiceController;

$serviceController = new ServiceController();
$rows = $serviceController->show();
?>

<main class="container mt-5 pt-4">
  <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['flash_message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="fw-bold">
        <i class="bi bi-tools me-3"></i>Layanan Servis
      </h2>
      <p class="text-muted">Kelola data layanan servis yang terdaftar</p>
    </div>
    <a href="tambah_services.php" class="btn btn-dark rounded-3 px-3 py-2 d-flex align-items-center gap-2">
      <i class="bi bi-plus-lg"></i> Tambah Servis
    </a>
  </div>

  <div class="card rounded-4 shadow-sm p-3">
    <div class="mb-3">
      <label for="searchInput" class="form-label fw-semibold">Daftar Layanan Servis</label>
      <div class="input-group">
        <span class="input-group-text bg-light border-0" id="searchIcon"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control border-0" placeholder="Cari servis..." aria-label="Cari servis" aria-describedby="searchIcon">
      </div>
    </div>

    <div class="table-responsive">
      <table id="servicesTable" class="table table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th class="text-center" scope="col"><input type="checkbox" id="selectAll" style="transform: scale(1.5);"></th>
            <th class="text-center" scope="col">No</th>
            <th class="text-center">Pelanggan</th>
            <th class="text-center">Produk</th>
            <th class="text-center">Jenis</th>
            <th class="text-center">Nomor Mesin</th>
            <th class="text-center">Keluhan</th>
            <th class="text-center">Biaya</th>
            <th class="text-center">Keterangan</th>
            <th class="text-center">Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)): ?>
            <?php $no = 1; ?>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><input type="checkbox" class="row-checkbox" value="<?= $row['id_servis'] ?>" style="transform: scale(1.5);"></td>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= htmlspecialchars($row['pelanggan_nama']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['merek_display'] ?? '-') ?> - <?= htmlspecialchars($row['model_display'] ?? '-') ?> - <?= htmlspecialchars($row['warna_display'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($row['jenis_display'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nomor_mesin'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($row['keluhan']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['biaya'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>

                <td class="text-center">
                  <?php if ($row['status'] === 'DONE'): ?>
                    <span class="badge bg-success">Selesai</span>
                  <?php elseif ($row['status'] === 'PROGRESS'): ?>
                    <span class="badge bg-warning text-dark">Proses</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Belum Ditentukan</span>
                  <?php endif; ?>
                </td>

                <td class="text-center">
                  <a href="edit_services.php?id=<?= $row['id_servis'] ?>" class="btn btn-outline-success btn-sm" title="Sunting" onclick="event.stopPropagation();"><i class="bi bi-pencil"></i></a>
                  <a href="#" class="btn btn-outline-danger btn-sm delete-btn" title="Hapus" onclick="confirmDelete(<?= $row['id_servis'] ?>); event.stopPropagation();"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="11" class="text-center text-muted">Belum ada data servis</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="mb-3">
      <button id="deleteSelectedBtn" class="btn btn-danger" style="display:none;">🗑 Hapus Terpilih</button>
    </div>
  </div>
</main>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus servis ini? Tindakan ini tidak dapat dibatalkan.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var table = $('#servicesTable').DataTable({
      "pageLength": 5,
      "lengthMenu": [
        [5, 10, 15, 25, -1],
        [5, 10, 15, 25, "Semua"]
      ],
      "order": [
        [1, 'asc']
      ],
      "columnDefs": [{
        "orderable": false,
        "targets": [0, 10]
      }],
      dom: 'rtip',
      buttons: [],
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

    $('#searchInput').on('keyup', function() {
      table.search(this.value).draw();
    });

    // Handle select all checkbox
    $('#selectAll').on('change', function() {
      $('.row-checkbox').prop('checked', this.checked);
      toggleDeleteButton();
    });

    // Handle individual row checkboxes
    $(document).on('change', '.row-checkbox', function() {
      var totalCheckboxes = $('.row-checkbox').length;
      var checkedCheckboxes = $('.row-checkbox:checked').length;
      $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
      toggleDeleteButton();
    });

    // Function to show/hide delete button
    function toggleDeleteButton() {
      if ($('.row-checkbox:checked').length > 0) {
        $('#deleteSelectedBtn').show();
      } else {
        $('#deleteSelectedBtn').hide();
      }
    }

    $('#deleteSelectedBtn').on('click', function() {
      var checkedBoxes = $('.row-checkbox:checked');
      if (checkedBoxes.length === 0) {
        alert('Silakan pilih setidaknya satu baris untuk dihapus.');
        return;
      }
      if (confirm('Apakah Anda yakin ingin menghapus servis yang dipilih?')) {
        var ids = [];
        checkedBoxes.each(function() {
          ids.push($(this).val());
        });
        if (ids.length > 0) {
          $.ajax({
            url: 'hapus_services.php',
            type: 'POST',
            data: {
              ids: ids
            },
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                location.reload();
              } else {
                alert('Gagal menghapus servis yang dipilih: ' + response.message);
              }
            },
            error: function() {
              alert('Gagal menghapus servis yang dipilih.');
            }
          });
        }
      }
    });
  });

  // Variable to store the ID of the service to delete
  var deleteId = null;

  // Function to confirm delete
  function confirmDelete(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
  }

  // Handle confirm delete button click
  $('#confirmDeleteBtn').on('click', function() {
    if (deleteId) {
      window.location.href = 'hapus_services.php?id=' + deleteId;
    }
  });
</script>

<?php include "../includes/footer.php"; ?>