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
<main>
    <h4 class="center-align" style="margin-top: 40px; margin-bottom: 20px;">Warenkorb</h4>
    <div class="row">
        <div class="col s12 m12 l12 card-panel">
        <table class="striped">
            <thead>
            <tr>
                <th>Produkt</th>
                <th>Preis</th>
                <th>Menge</th>
                <th>Gesamt</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $warenkorb = [
                ['name' => 'Produkt 1', 'preis' => 19.99, 'menge' => 2],
                ['name' => 'Produkt 2', 'preis' => 29.99, 'menge' => 1],
                ['name' => 'Produkt 3', 'preis' => 39.99, 'menge' => 3],
            ];
    
            $gesamt = 0;
            foreach ($warenkorb as $item) {
                $gesamt += $item['preis'] * $item['menge'];
                echo "<tr>";
                echo "<td>{$item['name']}</td>";
                echo "<td>{$item['preis']} €</td>";
                echo "<td>{$item['menge']}</td>";
                echo "<td>" . ($item['preis'] * $item['menge']) . " €</td>";
                echo "</tr>";

            }
            
                ?>
                
                <td colspan="3" class="right-align"><strong>Gesamt:</strong></td>
                </tbody></table>
                <button class="btn deep-purple darken-3" style="margin-top: 20px;">Zur Kasse</button>
                <button class="btn deep-purple darken-3" style="margin-top: 20px;">Weiter einkaufen</button></div>
</main>

<?php
include 'footer.php';
?>
</body>
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

</html>