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
          <th class="text-center">No</th>
          <th class="text-center">Pelanggan</th>
          <th class="text-center">Produk</th>
          <th class="text-center">Merek</th>
          <th class="text-center">Jenis</th>
          <th class="text-center">Warna</th>
          <th class="text-center">Nomor Mesin</th>
          <th class="text-center">Keluhan</th>
          <th class="text-center">Status</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
        <tbody>
        <?php if (!empty($rows)): ?>
          <?php $no = 1; ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td class="text-center"><?= htmlspecialchars($row['pelanggan_nama']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['produk_display'] ?? '-') ?></td>
              <td class="text-center"><?= htmlspecialchars($row['merek_display'] ?? '-') ?></td>
              <td class="text-center"><?= htmlspecialchars($row['jenis_display'] ?? '-') ?></td>
              <td class="text-center"><?= htmlspecialchars($row['warna_display'] ?? '-') ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nomor_mesin'] ?? '-') ?></td>
              <td class="text-center"><?= htmlspecialchars($row['keluhan']) ?></td>

              <!-- ✅ Tampilkan status dengan badge -->
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
                <a href="edit_services.php?id=<?= $row['id_servis'] ?>" class="btn btn-outline-success btn-sm" title="Sunting"><i class="bi bi-pencil"></i></a>
                <a href="hapus_services.php?id=<?= $row['id_servis'] ?>" class="btn btn-outline-danger btn-sm delete-btn" title="Hapus"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="10" class="text-center text-muted">Belum ada data servis</td>
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

<script>
  $(document).ready(function() {
    var table = $('#servicesTable').DataTable({
      "pageLength": 5,
      "lengthMenu": [
        [5, 10, 15, 25, -1],
        [5, 10, 15, 25, "Semua"]
      ],
      "columnDefs": [{
        "orderable": false,
        "targets": [8]
      }],
      select: {
        style: 'multi'
      },
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

    $('#deleteSelectedBtn').on('click', function() {
      var selectedRows = table.rows({
        selected: true
      });
      if (selectedRows.count() === 0) {
        alert('Silakan pilih setidaknya satu baris untuk dihapus.');
        return;
      }
      if (confirm('Apakah Anda yakin ingin menghapus servis yang dipilih?')) {
        var ids = [];
        selectedRows.every(function(rowIdx) {
          var row = table.row(rowIdx).node();
          var href = $(row).find('a.btn-outline-danger').attr('href');
          var urlParams = new URLSearchParams(href.split('?')[1]);
          var id = urlParams.get('id');
          if (id) {
            ids.push(id);
          }
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

    table.on('select deselect', function() {
      var selectedRows = table.rows({
        selected: true
      }).count();
      if (selectedRows > 0) {
        $('#deleteSelectedBtn').show();
      } else {
        $('#deleteSelectedBtn').hide();
      }
    });
  });
</script>

<?php include "../includes/footer.php"; ?>
