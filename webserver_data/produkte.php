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

<?php include 'header.php'; ?>

  <div class="container">
  <div class="row" style="align-items: center; margin-bottom: 20px;">

    <div class="col s12 m6">
      <h4 class="left-align">Produkte</h4>
    </div>

    <div class="col s12 m6">
      <div class="input-field">
        <input id="produktsuche" type="text" class="validate">
        <label for="produktsuche">Suche nach Produkt...</label>
      </div>
    </div>

 
    <div class="col s12">
      <div class="input-field">
        <select id="filterOption">
          <option value="bestseller" selected>Bestseller</option>
          <option value="neueste">Neueste</option>
          <option value="preis_auf">Preis: aufsteigend</option>
          <option value="preis_ab">Preis: absteigend</option>
          <option value="bewertung">Beste Bewertung</option>
        </select>
        <label>Sortieren nach</label>
      </div>
    </div>
  </div>

 
  <div class="product-grid">
    <?php for ($i = 1; $i <= 12; $i++): ?>
      <div class="product-card" data-title="Produkt <?= $i ?>">
      <!--  <a href="product.php?id=<?//= $i ?>"> -->
        <a href="produkt.php">
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


<?php include 'footer.php'; ?>


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
