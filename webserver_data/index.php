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

  <section class="logoContainer">
    <div class="logos">
      <img src="img/mugler.png" alt="Mugler Logo" class="logo">
      <img src="img/font.png" alt="Alien Logo" class="logo">
    </div>
    
    <div class="parfümContainer">
      <img src="img/alien.webp" alt="Alien Perfume" class="parfüm">
    </div>
  </section>

  <section class="content">
    <div class="container"></div></div>
      <h4 class="center-align">Unsere Bestseller</h4>
      <div class="row">
        <div class="carousel carousel-slider">
          <?php for ($i = 1; $i <= 12; $i++): ?>
            <div class="carousel-item">
              <div class="card small"></div>
              <div class="card-image">
                <a href="product.php?id=<?= $i ?>"></a>
                <img src="img/alien.webp" alt="Produkt <?= $i ?>" class="responsive-img">
                </a>
              </div>
              <div class="card-content"></div>
                <span class="card-title truncate">Produkt <?= $i ?></span>
                <a href="add_to_cart.php?id=<?= $i ?>" class="btn-floating halfway-fab waves-effect waves-light deep-purple">
                <i class="material-icons">add_shopping_cart</i>
                </a>
              </div>
              </div>
            </div>
                  </a>
                </div>
                <div class="card-content">
                  <span class="card-title">Produkt <?= $i ?></span>
                  <a href="add_to_cart.php?id=<?= $i ?>" class="btn-floating halfway-fab waves-effect waves-light deep-purple">
                    <i class="material-icons">add_shopping_cart</i>
                  </a>
                </div>
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </section>
  <footer class="page-footer deep-purple darken-3">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Über uns</h5>
          <p class="grey-text text-lighten-4">Wir sind ein Online-Shop, der alles hat von A-Z außer Jeff Bezos.</p>
        </div>
        <div class="col l4 offset-l2 s12">
          <h5 class="white-text">Links</h5>
          <ul>
            <li><a class="grey-text text-lighten-3" href="#!">Home</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Produkte</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Über uns</a></li>
            <li><a class="grey-text text-lighten-3" href="#!">Kontakt</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
        © 2025 Amazing Shop
        <a class="grey-text text-lighten-4 right" href="#!">Datenschutz</a>
      </div>
    </div>
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
