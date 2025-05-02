<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontakt – Amazing Shop</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <nav>
      <div class="nav-wrapper deep-purple darken-3">
        <a href="index.php" class="brand-logo"><i class="material-icons">store</i>Amazing Shop</a>
        

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

<main>
  <h4 class="center-align" style="margin-top: 40px; margin-bottom: 20px;">Kontaktieren Sie uns</h4>
  <div class="row">
    <form class= "col s12 m12 l12 card-panel" action="kontakt_senden.php" method="POST">
      <div class="row">
        <div class="input-field col s12 m6">
          <input id="name" name="name" type="text" required>
          <label for="name">Name</label>
        </div>
        <div class="input-field col s12 m6">
          <input id="email" name="email" type="email" required>
          <label for="email">E-Mail</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <input id="betreff" name="betreff" type="text" required>
          <label for="betreff">Betreff</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <textarea id="nachricht" name="nachricht" class="materialize-textarea" required></textarea>
          <label for="nachricht">Nachricht</label>
        </div>
      </div>

      <div class="row center-align">
        <button type="submit" class="btn deep-purple darken-3">Absenden</button>
      </div>
    </form>
  </div>
</main>

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
        <li><a href="#!">Produkte</a></li>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
