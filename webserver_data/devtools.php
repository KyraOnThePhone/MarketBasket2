
<!DOCTYPE html>
</html>
<?php
session_start();

include 'devcheck.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amazing Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<main class="container">
    

  <h4>Rollen ändern</h4>

  <div class="input-field">
    <input type="text" id="searchUser">
    <label for="searchUser">Suche Benutzer</label>
  </div>

  <div id="userResults"></div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

  <script>
   document.addEventListener('DOMContentLoaded', function() {
    M.AutoInit(); 
  });

  $('#searchUser').on('keyup', function() {
    let query = $(this).val();
    if (query.length > 2) {
      $.post('searchUser.php', { search: query }, function(data) {
        $('#userResults').html(data);
        $('select').formSelect(); 
      });
    }
  });

  function changeRole(userId, newRole) {
    $.post('changerole.php', { id: userId, role: newRole }, function(response) {
      M.toast({html: response.message, classes: 'green'});
      $('#searchUser').keyup();
    }, 'json').fail(function() {
      M.toast({html: 'Fehler beim Ändern der Rolle!', classes: 'red'});
    });
  }
  </script>
<h4>Testdaten Generator</h4>



<form id="probabilityForm" class="card-panel">
  <div class="row">


    <div class="input-field col s6">
      <select id="groupSelect" required>
        <option value="" disabled selected>Gruppe wählen</option>
        <option value="Oma">Oma</option>
        <option value="Gamer">Gamer</option>
        <option value="Kind">Kind</option>
        <option value="Alkoholiker">Alkoholiker</option>
        <option value="Rich Kids">Rich Kids</option>
      </select>
      <label>Gruppe auswählen</label>
    </div>


    <div class="input-field col s6">
      <select id="productSelect" required>
        <option value="" disabled selected>Produkt wählen</option>
        <option value="Mehl">Mehl</option>
        <option value="Holy Energy">Holy Energy</option>
        <option value="Controller">Controller</option>
        <option value="Tik Tok Mystery Box">Tik Tok Mystery Box</option>
        <option value="Pokemon Karten">Pokemon Karten</option>
        <option value="Pennergranate">Pennergranate</option>
        <option value="Vodka">Vodka</option>
        <option value="Sangria">Sangria</option>
        <option value="Mugler Alien">Mugler Alien</option>
        <option value="Iphone 16 Pro Max">Iphone 16 Pro Max</option>
        <option value="Nähset">Nähset</option>
      </select>
      <label>Produkt auswählen</label>
    </div>


    <div class="input-field col s6">
      <input id="probabilityInput" type="number" min="0" max="1" step="0.01" required>
      <label for="probabilityInput">Wahrscheinlichkeit (0 - 1)</label>
    </div>


    <div class="input-field col s6">
      <label>
        <input type="checkbox" id="isCombination"/>
        <span>2er-Kombination angeben</span>
      </label>
    </div>

  </div>


  <div class="row" id="comboSecondProductRow" style="display:none;">
    <div class="input-field col s6 offset-s3">
      <select id="productSelect2">
        <option value="" disabled selected>Zweites Produkt wählen</option>
        <option value="Mehl">Mehl</option>
        <option value="Holy Energy">Holy Energy</option>
        <option value="Controller">Controller</option>
        <option value="Tik Tok Mystery Box">Tik Tok Mystery Box</option>
        <option value="Pokemon Karten">Pokemon Karten</option>
        <option value="Pennergranate">Pennergranate</option>
        <option value="Vodka">Vodka</option>
        <option value="Sangria">Sangria</option>
        <option value="Mugler Alien">Mugler Alien</option>
        <option value="Iphone 16 Pro Max">Iphone 16 Pro Max</option>
        <option value="Nähset">Nähset</option>
      </select>
      <label>Zweites Produkt auswählen</label>
    </div>
  </div>

  <button class="btn waves-effect waves-light" type="submit">Testdaten erstellen</button>
</form>
<h5>Testdaten per CSV importieren</h5>
<div style="margin-top: 30px;">
  <a href="download_template.php" class="btn blue">
    <i class="material-icons left">file_download</i>CSV Vorlage herunterladen
  </a>
</div>

<form id="csvUploadForm" class="card-panel" enctype="multipart/form-data" method="POST" action="upload_csv.php">
  <div class="file-field input-field">
    <div class="btn">
      <span>Datei auswählen</span>
      <input type="file" name="csvFile" accept=".csv" required>
    </div>
    <div class="file-path-wrapper">
      <input class="file-path validate" type="text" placeholder="CSV Datei auswählen">
    </div>
  </div>
  <button type="submit" class="btn green">CSV hochladen und verarbeiten</button>
</form>

<div id="csvUploadResult" style="margin-top: 20px;"></div>


<div id="resultMessage" style="margin-top: 20px;"></div>
<script>document.addEventListener('DOMContentLoaded', function() {
  M.AutoInit();

  const isComboCheckbox = document.getElementById('isCombination');
  const comboSecondProductRow = document.getElementById('comboSecondProductRow');
  const form = document.getElementById('probabilityForm');
  const resultDiv = document.getElementById('resultMessage');

  isComboCheckbox.addEventListener('change', () => {
    comboSecondProductRow.style.display = isComboCheckbox.checked ? 'block' : 'none';
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const group = document.getElementById('groupSelect').value;
    const product1 = document.getElementById('productSelect').value;
    const probability = parseFloat(document.getElementById('probabilityInput').value);
    const isCombination = isComboCheckbox.checked;
    let product2 = null;

    if (isCombination) {
      product2 = document.getElementById('productSelect2').value;
      if (!product2) {
        M.toast({html: 'Bitte zweites Produkt für Kombination auswählen', classes: 'red'});
        return;
      }
      if (product1 === product2) {
        M.toast({html: 'Die Produkte in der Kombination müssen unterschiedlich sein', classes: 'red'});
        return;
      }
    }


    if (isNaN(probability) || probability < 0 || probability > 1) {
      M.toast({html: 'Wahrscheinlichkeit muss zwischen 0 und 1 liegen', classes: 'red'});
      return;
    }

    const postData = {
      group,
      product1,
      probability,
      isCombination
    };

    if (isCombination) {
      postData.product2 = product2;
    }

    $.ajax({
      url: 'api/testdata.php',
      method: 'POST',
      data: JSON.stringify(postData),
      contentType: 'application/json',
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          resultDiv.innerHTML = `<span class="green-text">${response.message}</span>`;
        } else {
          resultDiv.innerHTML = `<span class="red-text">${response.message}</span>`;
        }
      },
      error: function() {
        resultDiv.innerHTML = `<span class="red-text">Fehler bei der Kommunikation mit dem Server.</span>`;
      }
    });
  });
});
</script>
  </main>
<?php
include 'footer.php';   
?>

</body>
</html>
