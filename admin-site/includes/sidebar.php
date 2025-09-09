<!-- SIDEBAR -->
<aside class="sidebar">
  <ul>
<<<<<<< HEAD
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    function isActive($page, $current_page)
    {
      return $page === $current_page ? 'active' : '';
    }
    ?>
    <li><a class="nav-link <?= isActive('index_admin.php', $current_page) ?>" href="index_admin.php">Home</a></li>
    <li><a class="nav-link <?= isActive('index_products.php', $current_page) ?>" href="products/index_products.php">Our Product</a></li>
    <li><a class="nav-link <?= isActive('index_customers.php', $current_page) ?>" href="customers/index_customers.php">Customers</a></li>
    <li><a class="nav-link <?= isActive('index_services.php', $current_page) ?>" href="services/index_services.php">Service</a></li>
    <li><a class="nav-link <?= isActive('index_transactions.php', $current_page) ?>" href="transactions/index_transactions.php">Transaksi</a></li>
    <li><a class="nav-link btn-logout" href="logout_admin.php">Logout</a></li>
=======
    <li><a href="/Rolis/admin-site/index_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_admin.php' ? 'active' : '' ?>">Home</a></li>
    <li><a href="/Rolis/admin-site/products/index_products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_products.php' ? 'active' : '' ?>">Our Product</a></li>
    <li><a href="/Rolis/admin-site/customers/index_customers.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_customers.php' ? 'active' : '' ?>">Customers</a></li>
    <li><a href="/Rolis/admin-site/services/index_services.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_services.php' ? 'active' : '' ?>">Service</a></li>
    <li><a href="/Rolis/admin-site/transactions/index_transactions.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index_transactions.php' ? 'active' : '' ?>">Transaksi</a></li>
    <li><a href="/Rolis/contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
    <li><a href="/Rolis/admin-site/logout_admin.php" class="btn-logout">🚪 Logout</a></li>
>>>>>>> e99b2004c3d00dca839c8d3ba2f3582d1c89f586
  </ul>
</aside>
