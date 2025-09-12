<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php"; // koneksi database
require_once "../controllers/TransactionController.php";
$transactionController = new Adminsite\Controllers\TransactionController();

// Query ambil semua data transaksi
$rows = $transactionController->show()->fetch_all(MYSQLI_ASSOC);
?>

<main class="container mt-5 pt-4">
  <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['flash_message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📝 Transaksi</h1>
    <a href="tambah_transactions.php" class="btn btn-primary">➕ Tambah Transaksi</a>
  </div>

  <div class="table-responsive">
    <table id="transaksiTable" class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th class="text-center" scope="col">No</th>
          <th class="text-center">Pelanggan</th>
          <th class="text-center">Produk</th>
          <th class="text-center">Nomor Mesin</th>
          <th class="text-center">Nomor Body</th>
          <th class="text-center">Tanggal Garansi</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)): ?>
          <?php $no = 1; ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nama_produk']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nomor_mesin']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['nomor_body']) ?></td>
              <td class="text-center"><?= date("d-m-Y", strtotime($row['tanggal_garansi'])) ?></td>
              <td class="text-center">
                <a href="edit_transactions.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-outline-success action-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                <a href="hapus_transactions.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-outline-danger action-btn" title="Delete" onclick="return confirm('Yakin hapus transaksi ini?')"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>

    </table>
  </div>

  <div class="mb-3">
    <button id="deleteSelectedBtn" class="btn btn-danger" style="display:none;">🗑 Delete Selected</button>
  </div>
</main>

<script>
  $(document).ready(function() {
    var table = $('#transaksiTable').DataTable({
      "columnDefs": [{
        "orderable": false,
        "targets": [6]
      }],
      select: {
        style: 'multi'
      },
      dom: 'Bfrtip',
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

    $('#deleteSelectedBtn').on('click', function() {
      var selectedRows = table.rows({
        selected: true
      });
      if (selectedRows.count() === 0) {
        alert('Silakan pilih setidaknya satu baris untuk dihapus.');
        return;
      }
      if (confirm('Apakah Anda yakin ingin menghapus transaksi yang dipilih?')) {
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
          // Send AJAX request to delete multiple transactions
          $.ajax({
            url: 'hapus_transactions.php',
            type: 'POST',
            data: {
              ids: ids
            },
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                location.reload();
              } else {
                alert('Gagal menghapus transaksi yang dipilih: ' + response.message);
              }
            },
            error: function() {
              alert('Gagal menghapus transaksi yang dipilih.');
            }
          });
        }
      }
    });

    // Show/hide delete button based on selection
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