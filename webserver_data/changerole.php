<?php
session_start();
include 'db.php';
include 'devcheck.php';


if (isset($_POST['id'], $_POST['role'])) {
    $id = (int)$_POST['id'];
    $role = (int)$_POST['role'];

    if (!in_array($role, [1, 2, 3])) {
        echo json_encode(['message' => 'Ungültige Rolle.']);
        exit;
    }

    $sql = "UPDATE Accounts SET role_id = ? WHERE id = ?";
    $params = [$role, $id];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo json_encode(['message' => 'Fehler beim Aktualisieren.']);
        exit;
    }

    echo json_encode(['message' => 'Rolle erfolgreich geändert.']);
}
?>
