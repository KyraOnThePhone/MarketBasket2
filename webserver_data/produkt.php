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

  <link rel="stylesheet" href="produktstyle.css">
   <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
  <main class="produktmain">
 <section class="produkt-wrapper">
  <div class="produktcontainer">
    <img src="img/alien.webp" alt="Alien Perfume">
  </div>
  <div class="produkttextcontainer">
    <h2>Mugler</h2>
    <h3>Alien Hypersense</h3>
    <p>Damenparfüm</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed diam nonummy...</p>
    <button class="add2cart">In den Warenkorb</button>
    <div class="rating">
      <span class="stars">⭐⭐⭐⭐⭐</span>
      <span class="reviews">(123 Bewertungen)</span>

  </div>
  </div> 
</section>
  
  </main>
  
 <?php include 'footer.php'; ?>
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>