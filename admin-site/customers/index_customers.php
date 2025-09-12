   <?php
    session_start();
    include "../includes/header.php";
    include "../includes/sidebar.php";
    require_once "../includes/db.php";

    // Ambil semua data pelanggan
    $result = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
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
     <div class="d-flex justify-content-between align-items-center mb-4">
       <h1>👤 Customers</h1>
       <a href="tambah_customers.php" class="btn btn-primary">➕ Tambah Customer</a>
     </div>

     <div class="table-responsive">
       <table id="customersTable" class="table table-striped table-hover">
         <thead class="table-dark">
           <tr>
             <th class="text-center" scope="col">No</th>
             <th class="text-center">Nama</th>
             <th class="text-center">No HP</th>
             <th class="text-center">No KTP</th>
             <th class="text-center">Alamat</th>
             <th class="text-center">Aksi</th>
           </tr>
         </thead>
         <tbody>
           <?php if (!empty($rows)): ?>
             <?php $no = 1; ?>
             <?php foreach ($rows as $row): ?>
               <tr>
                 <td class="text-center"><?= $no++ ?></td>
                 <td class="text-center"><?= htmlspecialchars($row['nama']) ?></td>
                 <td class="text-center"><?= htmlspecialchars($row['no_hp']) ?></td>
                 <td class="text-center"><?= htmlspecialchars($row['no_ktp']) ?></td>
                 <td class="text-center"><?= htmlspecialchars($row['alamat']) ?></td>
                 <td class="text-center">
                   <a href="edit_customers.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-outline-success action-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                   <a href="hapus_customers.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-outline-danger action-btn" title="Delete" onclick="return confirm('Yakin mau hapus?')"><i class="bi bi-trash"></i></a>
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
       var table = $('#customersTable').DataTable({
         "columnDefs": [{
           "orderable": false,
           "targets": [5]
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
     });
   </script>

   <?php include "../includes/footer.php"; ?>