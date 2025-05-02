<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Amazing Shop</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header>
    <nav>
      <div class="nav-wrapper deep-purple darken-3">
        <a href="index.html" class="brand-logo"><i class="material-icons">store</i>Amazing Shop</a>
        

        <ul class="right hide-on-med-and-down">
          
          <li><i class="material-icons">shopping_cart</i></li>
          
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE): ?>
              <li><i class="material-icons">account_box</i></li>
              <li><?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?></li>

              <?php if (in_array('admin', $_SESSION['permissions'])): ?>
                  <li><a href="visualizer.php">AdminTools</a></li>
              <?php endif; ?>

              <?php if (in_array('dev', $_SESSION['permissions'])): ?>
                  <li><a href="visualizer.php">AdminTools</a></li>
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

  <section class="content">
  <div class="container">
    <h4 class="center-align">Unsere Bestseller</h4>
    <div class="product-grid">
      <?php for ($i = 1; $i <= 12; $i++): ?>
        <div class="product-card">
          <a href="product.php?id=<?= $i ?>">
            <img src="img/alien.webp" alt="Produkt <?= $i ?>" class="product-image">
          </a>
          <div class="product-info">
            <span class="product-title">Produkt <?= $i ?></span>
            <a href="add_to_cart.php?id=<?= $i ?>" class="btn-add-to-cart">
              <i class="material-icons">add_shopping_cart</i>
            </a>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<footer class="shop-footer">
  <div class="footer-content container">
    <div class="footer-section about">
      <h5>Über uns</h5>
      <p class="tooltipped" data-position="top" data-tooltip="Ja, wir sind das bessere Amazon!">
        Wir sind ein Online-Shop, der alles hat von A-Z außer Jeff Bezos.
      </p>
    </div>
    <div class="footer-section links">
      <h5>Links</h5>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="produkte.php">Produkte</a></li>
        <li><a href="about.php">Über uns</a></li>
        <li><a href="contact.php">Kontakt</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <span>© 2025 Amazing Shop</span>
      <a href="#!" class="right">Datenschutz</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const elems = document.querySelectorAll('.tooltipped');
      M.Tooltip.init(elems);
    });
  </script>
</footer>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const elems = document.querySelectorAll('.carousel');
      M.Carousel.init(elems, {
        fullWidth: true,
        indicators: true
      });
      const carousels = document.querySelectorAll('.carousel-slider');
      carousels.forEach(carousel => {
        M.Carousel.init(carousel, {
          fullWidth: true,
          indicators: true
        });
      });
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
