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

<?php include 'header.php'; ?>


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
  <div class="section" style="margin-top: 60px; margin-bottom: 40px;">
  <div class="divider"></div>
  </div>


    <section class="card-panel" style="margin-top: 40px;">
    <h5>Impressum</h5>
    <p><strong>Angaben gemäß § 5 TMG:</strong></p>
    <p>Amazing Shop GmbH<br>
       Musterstraße 1<br>
       12345 Musterstadt<br>
       Deutschland</p>

    <p><strong>Vertreten durch:</strong><br>
       Max Mustermann</p>

    <p><strong>Kontakt:</strong><br>
       Telefon: +49 (0) 123 4567890<br>
       E-Mail: kontakt@amazingshop.de</p>

    <p><strong>Registereintrag:</strong><br>
       Eintragung im Handelsregister.<br>
       Registergericht: Amtsgericht Musterstadt<br>
       Registernummer: HRB 123456</p>

    <p><strong>Umsatzsteuer-ID:</strong><br>
       Umsatzsteuer-Identifikationsnummer gemäß §27 a Umsatzsteuergesetz: DE123456789</p>
  </section>

</main>

<?php
include 'footer.php';
?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
