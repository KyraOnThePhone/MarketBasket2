<header>
  <nav>
    <div class="nav-wrapper deep-purple darken-3">
      <a href="index.php" class="brand-logo"><i class="material-icons">store</i>Amazing Shop</a>
      <ul class="right hide-on-med-and-down">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE): ?>
            <li><i class="material-icons">account_box</i></li>
            <li><?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?></li>
            <?php if (in_array('admin', $_SESSION['permissions'])): ?>
                <li><a href="visualizer.php">AdminTools</a></li>
            <?php endif; ?>
            <?php if (in_array('dev', $_SESSION['permissions'])): ?>
                <li><a href="devtools.php">DevTools</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.html">Login</a></li>
            <li><a href="register.html">Registrieren</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
</header>