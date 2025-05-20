<?php
include 'db.php';

if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
    $sql = "SELECT id, username, role_id FROM Accounts WHERE username LIKE ?";
    $params = ["%$search%"];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo "<p>Fehler bei der Abfrage.</p>";
        exit;
    }

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<div class='section'>
                <strong>{$row['username']}</strong> (aktuelle Rolle: {$row['role_id']})<br>
                <div class='input-field' style='width: 200px;'>
                  <select onchange='changeRole({$row['id']}, this.value)'>
                    <option value='' disabled selected>Wähle neue Rolle</option>
                    <option value='1' " . ($row['role_id'] == 1 ? 'selected' : '') . ">Admin</option>
                    <option value='2' " . ($row['role_id'] == 2 ? 'selected' : '') . ">Dev</option>
                    <option value='3' " . ($row['role_id'] == 3 ? 'selected' : '') . ">User</option>
                  </select>
                </div>
              </div>";
    }

    sqlsrv_free_stmt($stmt);
}
?>
