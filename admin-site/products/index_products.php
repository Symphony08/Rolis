<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php"; // koneksi database

// Ambil data produk + nama merek
$query = "SELECT p.*, m.value AS nama_merek
          FROM produk p
          JOIN merek m ON p.merek_id = m.id_merek
          ORDER BY p.id_produk DESC";
$result = mysqli_query($conn, $query);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
        <i class="bi bi-box-seam me-3"></i>Produk Kami</h2>
      <p class="text-muted">Kelola data sepeda dan motor listrik yang tersedia</p>
    </div>
    <a href="tambah_products.php" class="btn btn-dark rounded-3 px-3 py-2 d-flex align-items-center gap-2">
      <i class="bi bi-plus-lg"></i> Tambah Produk
    </a>
  </div>

  <div class="card rounded-4 shadow-sm p-3">
    <div class="mb-3">
      <label for="searchInput" class="form-label fw-semibold">Daftar Produk</label>
      <div class="input-group">
        <span class="input-group-text bg-light border-0" id="searchIcon"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control border-0" placeholder="Cari produk..." aria-label="Cari produk" aria-describedby="searchIcon">
      </div>
    </div>

    <div class="table-responsive">
      <table id="productsTable" class="table table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th class="text-center" scope="col">No</th>
            <th class="text-center">Merek</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Jenis</th>
            <th class="text-center">Deskripsi</th>
            <th class="text-center">Warna</th>
            <th class="text-center">Harga</th>
            <th class="text-center">Foto</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)): ?>
            <?php $no = 1; ?>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nama_merek']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nama']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['jenis']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['warna']) ?></td>
                <td class="text-center">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td class="text-center">
                  <?php if (!empty($row['foto'])): ?>
                    <img src="<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" width="80" class="img-fluid" style="cursor:pointer;" onclick="openModal('<?= htmlspecialchars($row['foto']) ?>')">
                  <?php else: ?>
                    <span class="text-muted">Tidak ada</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <a href="edit_products.php?id=<?= $row['id_produk'] ?>" class="btn btn-outline-success action-btn" title="Sunting"><i class="bi bi-pencil"></i></a>
                  <a href="hapus_products.php?id=<?= $row['id_produk'] ?>" class="btn btn-outline-danger action-btn" title="Hapus" onclick="return confirm('Yakin hapus produk ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Full Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="Full Image" class="img-fluid">
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var table = $('#productsTable').DataTable({
      "pageLength": 5,
      "lengthMenu": [
        [5, 10, 15, 25, -1],
        [5, 10, 15, 25, "Semua"]
      ],
      "columnDefs": [{
        "orderable": false,
        "targets": [7, 8]
      }],
      select: {
        style: 'multi'
      },
      dom: 'rtip',  // Removed default search box by excluding 'f' from dom
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

    // Custom search input integration
    $('#searchInput').on('keyup', function() {
      table.search(this.value).draw();
    });
  });

  function openModal(src) {
    $('#modalImage').attr('src', src);
    $('#imageModal').modal('show');
  }

  $('#deleteSelectedBtn').on('click', function() {
         var selectedRows = table.rows({
           selected: true
         });
         if (selectedRows.count() === 0) {
           alert('Silakan pilih setidaknya satu baris untuk dihapus.');
           return;
         }
         if (confirm('Apakah Anda yakin ingin menghapus pelanggan yang dipilih?')) {
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
             // Send AJAX request to delete multiple customers
             $.ajax({
               url: 'hapus_customers.php',
               type: 'POST',
               data: {
                 ids: ids
               },
               dataType: 'json',
               success: function(response) {
                 if (response.success) {
                   // Reload the page or table after deletion
                   location.reload();
                 } else {
                   alert('Gagal menghapus pelanggan yang dipilih: ' + response.message);
                 }
               },
               error: function() {
                 alert('Gagal menghapus pelanggan yang dipilih.');
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
</script>

<?php include "../includes/footer.php"; ?>
