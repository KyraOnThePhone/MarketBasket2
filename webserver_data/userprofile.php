<?php
session_start();


?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Benutzerprofil</title>


  <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>
<main>
<div class="container">
  <div class="section center-align">
    <h4>Willkommen, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?></h4>
  </div>

  <div class="row">
    <div class="col s12">
      <ul class="tabs deep-purple darken-3 z-depth-1">
        <li class="tab col s3"><a class="active" href="#orders">Bestellungen</a></li>
        <li class="tab col s3"><a href="#returns">Retouren</a></li>
        <li class="tab col s3"><a href="#wishlist">Wunschliste</a></li>
        <li class="tab col s3"><a href="#settings">Einstellungen</a></li>
      </ul>
    </div>

    <div id="orders" class="col s12 tab-content white z-depth-1">
      <h5 class="purple-text">Deine Bestellungen</h5>
      <p>Hier kannst du deine bisherigen Bestellungen einsehen.</p>
    </div>

    <div id="returns" class="col s12 tab-content white z-depth-1">
      <h5 class="purple-text">Deine Retouren</h5>
      <p>Hier kannst du deine Rücksendungen verwalten.</p>
    </div>

    <div id="wishlist" class="col s12 tab-content white z-depth-1">
      <h5 class="purple-text">Deine Wunschliste</h5>
      <p>Hier kannst du deine Wunschprodukte einsehen.</p>
    </div>

    <div id="settings" class="col s12 tab-content white z-depth-1">
      <h5 class="purple-text">Kontoeinstellungen</h5>
      <p>Hier kannst du deine Kontoeinstellungen bearbeiten.</p>
    </div>
  </div>
</div>
</main>

<?php include 'footer.php'; ?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.tabs');
    M.Tabs.init(tabs);
  });
</script>

</body>
</html>
