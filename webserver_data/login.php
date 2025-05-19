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

// SQL-Query, um Benutzerinformationen und Rolle zu bekommen
$sql = "SELECT Accounts.id, Accounts.password, Accounts.role_id, Roles.role_name FROM Accounts 
        JOIN Roles ON Accounts.role_id = Roles.id 
        WHERE Accounts.username = ?";
$params = array($_POST['uname']);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($stmt)) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $id = $row['id'];
    $password = $row['password'];
    $role_id = $row['role_id'];
    $role_name = $row['role_name']; // Rolle des Benutzers

    // Passwort überprüfen
    if (password_verify($_POST['pw'], $password)) {
        // Session starten
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['uname'];
        $_SESSION['id'] = $id;
        $_SESSION['role_id'] = $role_id;  // Rolle speichern
        $_SESSION['role_name'] = $role_name;  // Rolle speichern

        // Berechtigungen des Benutzers abrufen
        $permission_sql = "SELECT permission_name FROM permissions WHERE role_id = ?";
        $permission_stmt = sqlsrv_query($conn, $permission_sql, array($role_id));

        if ($permission_stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Berechtigungen in Session speichern
        $_SESSION['permissions'] = [];
        while ($permission_row = sqlsrv_fetch_array($permission_stmt, SQLSRV_FETCH_ASSOC)) {
            $_SESSION['permissions'][] = $permission_row['permission_name'];
        }

        sqlsrv_free_stmt($permission_stmt);

        // Weiterleitung zur entsprechenden Seite je nach Rolle
        header('Location: index.php');
        exit;
    } else {
        echo 'Passwort stimmt nicht mit dem Username überein';
    }
} else {
    echo 'Username existiert nicht';
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
