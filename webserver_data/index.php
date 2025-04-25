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
    <h2>Weitere Produkte</h2>
    <p>Hier könnten weitere Inhalte stehen...</p>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
