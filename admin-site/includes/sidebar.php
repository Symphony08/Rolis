<!-- SIDEBAR -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="list-group list-group-flush">
      <a href="/Rolis/admin-site/index_admin.php" class="list-group-item list-group-item-action <?= basename($_SERVER['PHP_SELF']) == 'index_admin.php' ? 'active' : '' ?>">
        Beranda
      </a>
      <a class="list-group-item list-group-item-action <?= in_array(basename($_SERVER['PHP_SELF']), ['index_products.php', 'tambah_products.php', 'edit_products.php']) ? 'active' : '' ?>" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-products" aria-expanded="false" aria-controls="submenu-products">
        Data Produk
      </a>
      <div class="collapse" id="submenu-products">
        <a href="/Rolis/admin-site/products/index_products.php" class="list-group-item list-group-item-action ps-4 <?= basename($_SERVER['PHP_SELF']) == 'index_products.php' ? 'active' : '' ?>">
          Lihat Produk
        </a>
        <a href="/Rolis/admin-site/products/tambah_products.php" class="list-group-item list-group-item-action ps-4 <?= basename($_SERVER['PHP_SELF']) == 'tambah_products.php' ? 'active' : '' ?>">
          Tambah Produk
        </a>
      </div>
      <a class="list-group-item list-group-item-action <?= in_array(basename($_SERVER['PHP_SELF']), ['index_customers.php', 'tambah_customers.php', 'edit_customers.php']) ? 'active' : '' ?>" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-customers" aria-expanded="false" aria-controls="submenu-customers">
        Data Pelanggan
      </a>
      <div class="collapse" id="submenu-customers">
        <a href="/Rolis/admin-site/customers/index_customers.php" class="list-group-item list-group-item-action ps-4 <?= basename($_SERVER['PHP_SELF']) == 'index_customers.php' ? 'active' : '' ?>">
          Lihat Pelanggan
        </a>
        <a href="/Rolis/admin-site/customers/tambah_customers.php" class="list-group-item list-group-item-action ps-4 <?= basename($_SERVER['PHP_SELF']) == 'tambah_customers.php' ? 'active' : '' ?>">
          Tambah Pelanggan
        </a>
      </div>
      <a class="list-group-item list-group-item-action <?= in_array(basename($_SERVER['PHP_SELF']), ['index_transactions.php', 'tambah_transactions.php', 'edit_transactions.php']) ? 'active' : '' ?>" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-transactions" aria-expanded="false" aria-controls="submenu-transactions">
        Data Transaksi
      </a>
      <div class="collapse" id="submenu-transactions">
        <a href="/Rolis/admin-site/transactions/index_transactions.php" class="list-group-item list-group-item-action ps-4 <?= basename($_SERVER['PHP_SELF']) == 'index_transactions.php' ? 'active' : '' ?>">
          Lihat Transaksi
        </a>
        <a href="/Rolis/admin-site/transactions/tambah_transactions.php" class="list-group-item list-group-item-action ps-4 <?= basename($_SERVER['PHP_SELF']) == 'tambah_transactions.php' ? 'active' : '' ?>">
          Tambah Transaksi
        </a>
      </div>
      <a class="list-group-item list-group-item-action <?= in_array(basename($_SERVER['PHP_SELF']), ['index_services.php', 'tambah_services.php', 'edit_services.php']) ? 'active' : '' ?>" href="#" data-bs-toggle="collapse" data-bs-target="#submenu-services" aria-expanded="false" aria-controls="submenu-services">
        Data Servis
      </a>
      <div class="collapse" id="submenu-services">
        <a href="/Rolis/admin-site/services/index_services.php" class="list-group-item list-group-item-action ps-4 <?= basename($_SERVER['PHP_SELF']) == 'index_services.php' ? 'active' : '' ?>">
          Lihat Servis
        </a>
        <a href="/Rolis/admin-site/services/tambah_services.php" class="list-group-item list-group-item-action ps-4 <?= basename($_SERVER['PHP_SELF']) == 'tambah_services.php' ? 'active' : '' ?>">
          Tambah Servis
        </a>
      </div>
      <a href="/Rolis/admin-site/settings/index_settings.php" class="list-group-item list-group-item-action <?= basename($_SERVER['PHP_SELF']) == 'index_settings.php' ? 'active' : '' ?>">
        Pengaturan
      </a>
      <a href="/Rolis/admin-site/logout_admin.php" class="list-group-item list-group-item-action text-danger">
        🚪 Keluar
      </a>
    </div>
  </div>
</div>