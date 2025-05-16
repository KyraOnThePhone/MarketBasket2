<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();

// Verbindung zur Datenbank herstellen
$serverName = "sql, 1433"; 
$connectionInfo = array(
    "Database" => "Login",
    "UID" => "sa",
    "PWD" => "BratwurstIN23!",
    "TrustServerCertificate" => true // Zertifikat ignorieren
);
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT id FROM Accounts WHERE username = ?";
$params = array($_POST['username']);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (isset($_POST['username'], $_POST['password'], $_POST['repassword'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    if (strlen($password) < 8) {
        echo "Das Passwort muss mindestens 8 Zeichen lang sein.";
        exit;
    }

    if ($password !== $repassword) {
        echo "Die Passwörter stimmen nicht überein.";
        exit;
    }

    $sql_check = "SELECT id FROM Accounts WHERE username = ?";
    $params_check = array($username); // Existiert der User?
    $stmt_check = sqlsrv_query($conn, $sql_check, $params_check);

    if ($stmt_check === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt_check)) {
        echo "Benutzername existiert bereits.";
        sqlsrv_free_stmt($stmt_check);
        sqlsrv_close($conn);
        exit;
    }

    sqlsrv_free_stmt($stmt_check);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO Accounts (username, password, role_id) VALUES (?, ?, ?)";
    $params_insert = array($username, $hashed_password, 2); // Rolle Standard 2 für "User"

    $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

    if ($stmt_insert === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt_insert);
    sqlsrv_close($conn);

    echo "<script>
        alert('Registrierung erfolgreich! Sie werden nun zur Startseite weitergeleitet.');
        window.location.href = 'index.php';
    </script>";
    exit;
} else {
    echo "Bitte füllen Sie alle Felder aus.";
}
?>
