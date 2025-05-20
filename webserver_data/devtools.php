
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

  </main>
<?php
include 'footer.php';   
?>

</body>
</html>
