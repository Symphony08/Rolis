<!-- SIDEBAR -->
<aside class="sidebar">
  <ul>
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
  </ul>
</aside>
