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

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="fw-bold">
        <i class="bi bi-receipt me-3"></i>Transaksi
      </h2>
      <p class="text-muted">Kelola data transaksi yang tersedia</p>
    </div>
    <a href="tambah_transactions.php" class="btn btn-dark rounded-3 px-3 py-2 d-flex align-items-center gap-2">
      <i class="bi bi-plus-lg"></i> Tambah Transaksi
    </a>
  </div>

  <div class="card rounded-4 shadow-sm p-3">
    <div class="mb-3">
      <label for="searchInput" class="form-label fw-semibold">Daftar Transaksi</label>
      <div class="input-group">
        <span class="input-group-text bg-light border-0" id="searchIcon"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control border-0" placeholder="Cari transaksi..." aria-label="Cari transaksi" aria-describedby="searchIcon">
      </div>
    </div>

    <div class="table-responsive">
      <table id="transactionsTable" class="table table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th class="text-center" scope="col"><input type="checkbox" id="selectAll"></th>
            <th class="text-center" scope="col">No</th>
            <th class="text-center">Pelanggan</th>
            <th class="text-center">Produk</th>
            <th class="text-center">Warna</th>
            <th class="text-center">Tipe</th>
            <th class="text-center">Nomor Mesin</th>
            <th class="text-center">Nomor Body</th>
            <th class="text-center">Tanggal Berakhir Garansi</th>
            <th class="text-center">Tanggal Transaksi</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)): ?>
            <?php $no = 1; ?>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><input type="checkbox" class="row-checkbox" value="<?= $row['id_transaksi'] ?>"></td>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nama_produk']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['warna']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['tipe']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nomor_mesin']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nomor_body']) ?></td>
                <td class="text-center"><?= date("d-m-Y", strtotime($row['tanggal_garansi'])) ?></td>
                <td class="text-center"><?= date("d-m-Y", strtotime($row['tanggal_transaksi'])) ?></td>
                <td class="text-center">
                  <a href="edit_transactions.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-outline-success btn-sm" title="Sunting" onclick="event.stopPropagation();"><i class="bi bi-pencil"></i></a>
                  <a href="#" class="btn btn-outline-danger btn-sm delete-btn" title="Hapus" onclick="confirmDelete(<?= $row['id_transaksi'] ?>); event.stopPropagation();"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>

      </table>
    </div>

    <div class="mb-3">
      <button id="deleteSelectedBtn" class="btn btn-danger" style="display:none;">🗑 Hapus Terpilih</button>
    </div>
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
        Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.
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
    var table = $('#transactionsTable').DataTable({
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
        "targets": [0, 7, 8, 9]
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

    // Debounce function to delay search execution
    function debounce(func, delay) {
      let timeoutId;
      return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
      };
    }

    // Debounced search function
    const debouncedSearch = debounce(function(value) {
      table.search(value).draw();
    }, 300);

    // Custom search input integration
    $('#searchInput').on('keyup', function() {
      debouncedSearch(this.value);
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
      if (confirm('Apakah Anda yakin ingin menghapus transaksi yang dipilih?')) {
        var ids = [];
        checkedBoxes.each(function() {
          ids.push($(this).val());
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
  });

  // Variable to store the ID of the transaction to delete
  var deleteId = null;

  // Function to confirm delete
  function confirmDelete(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
  }

  // Handle confirm delete button click
  $('#confirmDeleteBtn').on('click', function() {
    if (deleteId) {
      window.location.href = 'hapus_transactions.php?id=' + deleteId;
    }
  });
</script>

<?php include "../includes/footer.php"; ?>