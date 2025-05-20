<?php
session_start();

include 'devcheck.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Rollenverwaltung</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body class="container">

  <h4>Rollen ändern</h4>

  <div class="input-field">
    <input type="text" id="searchUser">
    <label for="searchUser">Suche Benutzer</label>
  </div>

  <div id="userResults"></div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $('#searchUser').on('keyup', function() {
      let query = $(this).val();
      if (query.length > 2) {
        $.post('searchUser.php', { search: query }, function(data) {
          $('#userResults').html(data);
        });
      }
    });

    function changeRole(userId, newRole) {
      $.post('changerole.php', { id: userId, role: newRole }, function(response) {
        alert(response.message);
        $('#searchUser').keyup();
      }, 'json');
    }
  </script>

</body>
</html>
