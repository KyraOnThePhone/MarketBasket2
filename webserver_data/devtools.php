<?php session_start(); include 'devcheck.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>DevTools - Amazing Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"  rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>

        .tabs .tab a {
            color: #6a1b9a;
            font-weight: bold;
        }
        .tabs .tab a:hover {
            background-color: #f3e5f5 !important;
            color: #8e24aa !important;
        }
        .tabs .indicator {
            background-color: #6a1b9a !important;
        }
        .card-panel {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background-color: #6a1b9a;
        }
        .btn-custom:hover {
            background-color: #4a148c;
        }
        .section-title {
            color: #6a1b9a;
            font-weight: bold;
        }
        .tab-content {
            padding: 30px;
            border-radius: 10px;
            margin-top: 20px;
            background-color: white;
        }

    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <ul class="tabs">
        <li class="tab col s6"><a href="#userSearch">Rollen ändern</a></li>
        <li class="tab col s6"><a href="#testdata">Testdaten generieren</a></li>
    </ul>

    <!-- Tab: Benutzerrollen -->
    <div id="userSearch" class="col s12 tab-content">
        <h5 class="section-title">Benutzer suchen und Rolle ändern</h5>
        <div class="input-field">
            <input type="text" id="searchUser" placeholder="Suche Benutzer..." autocomplete="off">
            <label for="searchUser">Benutzer suchen</label>
        </div>
        <div id="userResults"></div>
    </div>

    <!-- Tab: Testdaten generieren -->
    <div id="testdata" class="col s12 tab-content">
        <h5 class="section-title">Testdaten Generator</h5>
        <p>Hiermit kannst du auf Knopfdruck automatisch Testkunden, Warenkörbe und Bestellungen generieren.</p>
        <button id="generateTestDataBtn" class="btn btn-custom waves-effect waves-light">
            Testdaten erstellen
        </button>
        <div id="resultMessage" style="margin-top: 20px;"></div>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> 
<script>
$(document).ready(function () {
    $('.tabs').tabs();

    // Live Search for Users
    $('#searchUser').on('keyup', function () {
        let query = $(this).val();
        if (query.length > 2) {
            $.post('searchUser.php', { search: query }, function (data) {
                $('#userResults').html(data);
                $('select').formSelect();
            });
        } else {
            $('#userResults').empty();
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

    // Button: Testdaten generieren
    $('#generateTestDataBtn').on('click', function () {
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="material-icons left">autorenew</i>Lade...');

        $.ajax({
            url: 'testdata.php',
            method: 'POST',
            success: function(response) {
                $('#resultMessage').html(`<span class="green-text">${response}</span>`);
                M.toast({ html: "Testdaten erfolgreich erstellt!", classes: 'green' });
            },
            error: function(xhr) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    $('#resultMessage').html(`<span class="red-text">${res.message || 'Unbekannter Fehler'}</span>`);
                    M.toast({ html: "Fehler beim Erstellen der Testdaten.", classes: 'red' });
                } catch (e) {
                    $('#resultMessage').html(`<span class="red-text">Serverfehler!</span>`);
                    M.toast({ html: "Serverfehler bei Testdatenerstellung", classes: 'red' });
                }
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
</body>
</html>