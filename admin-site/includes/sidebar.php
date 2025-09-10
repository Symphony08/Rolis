<!-- SIDEBAR -->
<aside class="sidebar">
  <ul>
    <li><a href="/Rolis/admin-site/index_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_admin.php' ? 'active' : '' ?>">Home</a></li>
    <li><a href="/Rolis/admin-site/products/index_products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_products.php' ? 'active' : '' ?>">Our Product</a></li>
    <li><a href="/Rolis/admin-site/customers/index_customers.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_customers.php' ? 'active' : '' ?>">Customers</a></li>
    <li><a href="/Rolis/admin-site/services/index_services.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_services.php' ? 'active' : '' ?>">Service</a></li>
    <li><a href="/Rolis/admin-site/transactions/index_transactions.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_transactions.php' ? 'active' : '' ?>">Transaksi</a></li>
    <li><a href="/Rolis/contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
    <li><a href="/Rolis/admin-site/logout_admin.php" class="btn-logout">🚪 Logout</a></li>
  </ul>
</aside>
