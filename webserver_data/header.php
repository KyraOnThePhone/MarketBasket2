<?php
session_start();
?>
<header>
  <nav>
    <div class="nav-wrapper deep-purple darken-3">
      <a href="index.php" class="brand-logo"><i class="material-icons">store</i>Amazing Shop</a>
      <ul class="right hide-on-med-and-down">
        <li><a href="produkte.php">Produkte</a></li>
        <li><a href="about.php">Über uns</a></li>
        <li><a href="contact.php">Kontakt</a></li>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE): ?>
            <li><a href="userprofile.php"><i class="material-icons">account_box</i></a></li>
            <li><?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?></li>
            <li><a href="warenkorb.php"> <i class="material-icons">shopping_cart</i></a></li>
            <?php if (in_array('admin', $_SESSION['permissions']) || in_array('dev', $_SESSION['permissions'])): ?>
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