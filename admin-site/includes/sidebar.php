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
      <a href="/Rolis/admin-site/products/index_products.php" class="list-group-item list-group-item-action <?= basename($_SERVER['PHP_SELF']) == 'index_products.php' ? 'active' : '' ?>">
        Produk Kami
      </a>
      <a href="/Rolis/admin-site/customers/index_customers.php" class="list-group-item list-group-item-action <?= basename($_SERVER['PHP_SELF']) == 'index_customers.php' ? 'active' : '' ?>">
        Pelanggan
      </a>
      <a href="/Rolis/admin-site/services/index_services.php" class="list-group-item list-group-item-action <?= basename($_SERVER['PHP_SELF']) == 'index_services.php' ? 'active' : '' ?>">
        Layanan
      </a>
      <a href="/Rolis/admin-site/transactions/index_transactions.php" class="list-group-item list-group-item-action <?= basename($_SERVER['PHP_SELF']) == 'index_transactions.php' ? 'active' : '' ?>">
        Transaksi
      </a>
      <a href="/Rolis/contact.php" class="list-group-item list-group-item-action <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">
        Kontak
      </a>
      <a href="/Rolis/admin-site/logout_admin.php" class="list-group-item list-group-item-action text-danger">
        🚪 Keluar
      </a>
    </div>
  </div>
</div>