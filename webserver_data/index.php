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

<?php include 'header.php'; 
?>

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
  <div class="container">
    <h4 class="center-align">Unsere Bestseller</h4>
    <div class="product-grid">
      <?php for ($i = 1; $i <= 12; $i++): ?>
        <div class="product-card">
          <!--<a href="product.php?id=<? //= $i ?>"> -->
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
</section>

<?php
include 'footer.php';
?>

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
