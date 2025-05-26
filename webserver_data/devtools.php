<?php session_start(); include 'devcheck.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DevTools - Amazing Shop</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css " rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons " rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .card-panel {
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .btn-custom {
      background-color: #4527A0;
    }
    .btn-custom:hover {
      background-color: #311b92;
    }
    .section-title {
      color: #4527A0;
      font-weight: bold;
    }
    .tab-content {
      padding: 30px;
      border-radius: 10px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container" style="padding: 40px 0;">
  <!-- Materialize Tabs -->
  <ul class="tabs">
    <li class="tab col s3"><a href="#userSearch">Rollen ändern</a></li>
    <li class="tab col s3"><a href="#testdata">Testdaten Generator</a></li>
    <li class="tab col s3"><a href="#csvupload">CSV Import</a></li>
    <li class="tab col s3"><a href="#analysis">Marktanalyse</a></li>
    <li class="tab col s3"><a href="#excelupload">Excel Hochladen</a></li>
  </ul>

  <!-- Tab 1: Benutzer Suche & Rollenänderung -->
  <div id="userSearch" class="col s12 tab-content">
    <h5 class="section-title">Benutzer suchen und Rolle ändern</h5>
    <div class="input-field">
      <input type="text" id="searchUser" placeholder="Suche Benutzer..." autocomplete="off">
      <label for="searchUser">Benutzer suchen</label>
    </div>
    <div id="userResults"></div>
  </div>

  <!-- Tab 2: Testdaten Generator -->
  <div id="testdata" class="col s12 tab-content">
    <h5 class="section-title">Testdaten Generator</h5>
    <form id="probabilityForm">
      <div class="row">
        <div class="input-field col s12 m6">
          <select id="groupSelect" required>
            <option value="" disabled selected>Gruppe wählen</option>
            <option value="Oma">Oma</option>
            <option value="Gamer">Gamer</option>
            <option value="Kind">Kind</option>
            <option value="Alkoholiker">Alkoholiker</option>
            <option value="Rich Kids">Rich Kids</option>
          </select>
          <label>Kundengruppe</label>
        </div>
        <div class="input-field col s12 m6">
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
          <label>Hauptprodukt</label>
        </div>
        <div class="input-field col s12 m6">
          <input id="probabilityInput" type="number" min="0" max="1" step="0.01" required>
          <label for="probabilityInput">Wahrscheinlichkeit (0 - 1)</label>
        </div>
        <div class="col s12 m6">
          <p>
            <label>
              <input type="checkbox" id="isCombination"/>
              <span>2er-Kombination angeben</span>
            </label>
          </p>
        </div>
      </div>

      <div id="comboSecondProductRow" class="row" style="display:none;">
        <div class="input-field col s12 m6 offset-m3">
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
          <label>Zweites Produkt</label>
        </div>
      </div>

      <button class="btn btn-custom waves-effect waves-light" type="submit">Testdaten erstellen</button>
    </form>
    <div id="resultMessage" style="margin-top: 20px;"></div>
  </div>

  <!-- Tab 3: CSV Import -->
  <div id="csvupload" class="col s12 tab-content">
    <h5 class="section-title">CSV-Daten importieren</h5>
    <a href="download_template.php" class="btn btn-custom">
      <i class="material-icons left">file_download</i>CSV Vorlage herunterladen
    </a>
    <form id="csvUploadForm" enctype="multipart/form-data" method="POST" action="upload_csv.php" class="mt-3">
      <div class="file-field input-field">
        <div class="btn btn-custom">
          <span>Datei auswählen</span>
          <input type="file" name="csvFile" accept=".csv" required>
        </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text" placeholder="CSV Datei auswählen">
        </div>
      </div>
      <button type="submit" class="btn btn-custom green">CSV hochladen und verarbeiten</button>
    </form>
    <div id="csvUploadResult" style="margin-top: 20px;"></div>
  </div>

  <!-- Tab 4: Marktanalyse -->
  <div id="analysis" class="col s12 tab-content">
    <h5 class="section-title">Marktbasket Analyse Ergebnisse</h5>
    <button id="runAnalysisBtn" class="btn btn-custom waves-effect waves-light">Analyse starten</button>
    <div id="analysisResults" style="margin-top: 20px;"></div>
  </div>

  <!-- Tab 5: Excel Hochladen -->
  <div id="excelupload" class="col s12 tab-content">
    <h5 class="section-title">Excel mit Regeln hochladen</h5>
    <form id="excelUploadForm" enctype="multipart/form-data">
      <div class="file-field input-field">
        <div class="btn btn-custom">
          <span>Datei auswählen</span>
          <input type="file" name="excelFile" accept=".xlsx" required>
        </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text" placeholder="Excel-Datei (.xlsx)">
        </div>
      </div>
      <button type="submit" class="btn btn-custom green waves-effect waves-light">Excel verarbeiten</button>
    </form>
    <div id="excelUploadResult" style="margin-top: 20px;"></div>
  </div>

</div>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js "></script>
<script>
$(document).ready(function () {
  M.AutoInit();

  // Initialize Tabs
  $('.tabs').tabs();

  // Live Search for Users
  $('#searchUser').on('keyup', function () {
    let query = $(this).val();
    if (query.length > 2) {
      $.post('searchUser.php', { search: query }, function (data) {
        $('#userResults').html(data);
        $('select').formSelect();
      });
    }
  });

  // Role Change Handler
  window.changeRole = function(userId, newRole) {
    $.post('changerole.php', { id: userId, role: newRole }, function(response) {
      M.toast({html: response.message, classes: 'green'});
      $('#searchUser').keyup();
    }, 'json').fail(function() {
      M.toast({html: 'Fehler beim Ändern der Rolle!', classes: 'red'});
    });
  };

  // Combo Toggle
  const isComboCheckbox = document.getElementById('isCombination');
  const comboSecondProductRow = document.getElementById('comboSecondProductRow');

  isComboCheckbox.addEventListener('change', () => {
    comboSecondProductRow.style.display = isComboCheckbox.checked ? 'block' : 'none';
  });

  // Form Submit for Test Data
  document.getElementById('probabilityForm').addEventListener('submit', function(e) {
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
    if (isCombination) postData.product2 = product2;

    $.ajax({
      url: 'api/testdata.php',
      method: 'POST',
      data: JSON.stringify(postData),
      contentType: 'application/json',
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#resultMessage').html(`<span class="green-text">${response.message}</span>`);
        } else {
          $('#resultMessage').html(`<span class="red-text">${response.message}</span>`);
        }
      },
      error: function() {
        $('#resultMessage').html(`<span class="red-text">Fehler bei der Kommunikation mit dem Server.</span>`);
      }
    });
  });

  // CSV Upload Handler
  $('#csvUploadForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
      url: 'upload_csv.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        $('#csvUploadResult').html(`<span class="green-text">${response}</span>`);
      },
      error: function() {
        $('#csvUploadResult').html(`<span class="red-text">Fehler beim Hochladen der Datei.</span>`);
      }
    });
  });

  // Excel Upload Handler
  $('#excelUploadForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
      url: 'api/upload_excel.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        $('#excelUploadResult').html(`<span class="green-text">${response.message}</span>`);
      },
      error: function(xhr) {
        try {
          const res = JSON.parse(xhr.responseText);
          $('#excelUploadResult').html(`<span class="red-text">${res.message || 'Unbekannter Fehler'}</span>`);
        } catch (e) {
          $('#excelUploadResult').html(`<span class="red-text">Fehler bei der Verarbeitung</span>`);
        }
      }
    });
  });

  // Run Market Basket Analysis
  $('#runAnalysisBtn').on('click', function () {
    $.getJSON('api/analyze_basket.php', function(data) {
      if (!data.success) {
        $('#analysisResults').html(`<p class="red-text">${data.error}</p>`);
        return;
      }

      let html = `<table class="striped responsive-table centered">
        <thead><tr><th>Regel</th><th>Support</th><th>Confidence</th><th>Lift</th></tr></thead><tbody>`;
      
      data.rules.forEach(rule => {
        html += `<tr><td>${rule.rule}</td><td>${rule.support}</td><td>${rule.confidence}</td><td>${rule.lift}</td></tr>`;
      });

      html += '</tbody></table>';
      $('#analysisResults').html(html);
    });
  });
});
</script>
</body>
</html>