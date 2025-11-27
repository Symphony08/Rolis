<?php
session_start();
include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/db.php";

// Ambil data produk dengan JOIN ke tabel merek, model, dan warna
$query = "SELECT p.*, 
          m.value AS nama_merek,
          mo.value AS nama_model,
          w.value AS nama_warna
          FROM produk p
          LEFT JOIN merek m ON p.merek_id = m.id_merek
          LEFT JOIN model mo ON p.model_id = mo.id_model
          LEFT JOIN warna w ON p.warna_id = w.id_warna
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
        <i class="bi bi-box-seam me-3"></i>Produk Kami
      </h2>
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
            <th class="text-center" scope="col"><input type="checkbox" id="selectAll" style="transform: scale(1.5);"></th>
            <th class="text-center" scope="col">No</th>
            <th class="text-center">Merek</th>
            <th class="text-center">Model/Tipe</th>
            <th class="text-center">Warna</th>
            <th class="text-center">Jenis</th>
            <th class="text-center">Deskripsi</th>
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
                <td class="text-center"><input type="checkbox" class="row-checkbox" value="<?= $row['id_produk'] ?>" style="transform: scale(1.5);"></td>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nama_merek'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nama_model'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nama_warna'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($row['jenis'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($row['deskripsi'] ?? '-') ?></td>
                <td class="text-center"><?php if (is_null($row['harga'])): ?>-<?php else: ?>Rp <?= number_format($row['harga'], 0, ',', '.') ?><?php endif; ?></td>
                <td class="text-center">
                  <?php if (!empty($row['foto'])): ?>
                    <img src="<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nama_merek']) ?>" width="80" class="img-fluid" style="cursor:pointer;" onclick="event.stopPropagation(); openModal('<?= htmlspecialchars($row['foto']) ?>')">
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <a href="edit_products.php?id=<?= $row['id_produk'] ?>" class="btn btn-outline-success btn-sm" title="Sunting" onclick="event.stopPropagation();"><i class="bi bi-pencil"></i></a>
                  <a href="#" class="btn btn-outline-danger btn-sm delete-btn" title="Hapus" onclick="confirmDelete(<?= $row['id_produk'] ?>); event.stopPropagation();"><i class="bi bi-trash"></i></a>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.
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
    var table = $('#productsTable').DataTable({
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
        "targets": [0, 8, 9]
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

    $('#selectAll').on('change', function() {
      $('.row-checkbox').prop('checked', this.checked);
      toggleDeleteButton();
    });

    $(document).on('change', '.row-checkbox', function() {
      var totalCheckboxes = $('.row-checkbox').length;
      var checkedCheckboxes = $('.row-checkbox:checked').length;
      $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
      toggleDeleteButton();
    });

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
      if (confirm('Apakah Anda yakin ingin menghapus produk yang dipilih?')) {
        var ids = [];
        checkedBoxes.each(function() {
          ids.push($(this).val());
        });
        if (ids.length > 0) {
          $.ajax({
            url: 'hapus_products.php',
            type: 'POST',
            data: {
              ids: ids
            },
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                location.reload();
              } else {
                alert('Gagal menghapus produk yang dipilih: ' + response.message);
              }
            },
            error: function() {
              alert('Gagal menghapus produk yang dipilih.');
            }
          });
        }
      }
    });
  });

  function openModal(imageSrc) {
    $('#modalImage').attr('src', imageSrc);
    $('#imageModal').modal('show');
  }

  var deleteId = null;

  function confirmDelete(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
  }

  $('#confirmDeleteBtn').on('click', function() {
    if (deleteId) {
      window.location.href = 'hapus_products.php?id=' + deleteId;
    }
  });
</script>

<?php include "../includes/footer.php"; ?>