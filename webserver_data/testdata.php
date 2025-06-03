<?php
require 'dbshop.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔁 Statt Dateien werden hier feste Arrays verwendet
$namen = ['Max Mustermann', 'Lisa Müller', 'Ali Demir', 'Sophie Becker', 'Lukas Schmitt'];
$adressen = [
    'Musterstraße 1, 12345 Musterstadt',
    'Hauptweg 45, 67890 Beispielstadt',
    'Bahnhofstraße 12, 10115 Berlin',
    'Lindenweg 3, 54321 Testhausen',
    'Goethestraße 99, 89012 Beispielburg'
];

$gruppen = [];
$gruppenResult = sqlsrv_query($conn, "SELECT ID FROM Gruppen");
while ($row = sqlsrv_fetch_array($gruppenResult)) {
    $gruppen[] = $row['ID'];
}

sqlsrv_begin_transaction($conn);

try {
    for ($kundeIndex = 0; $kundeIndex < 1000; $kundeIndex++) {
        $name = $namen[array_rand($namen)];
        $adresse = $adressen[array_rand($adressen)];
        $userID = rand(1000, 9999);
        $gruppeID = $gruppen[array_rand($gruppen)];

        $stmt = sqlsrv_query($conn, "
            INSERT INTO Kunden (Name, Adresse, UserID, GruppeID)
            OUTPUT INSERTED.ID
            VALUES (?, ?, ?, ?)
        ", [$name, $adresse, $userID, $gruppeID]);

        if (!$stmt) {
            throw new Exception("Kunden einfügen fehlgeschlagen: " . print_r(sqlsrv_errors(), true));
        }

        $kundeRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $kundeID = $kundeRow['ID'];

        $stmt = sqlsrv_query($conn, "
            INSERT INTO Warenkorb (Timestamp, UserID)
            OUTPUT INSERTED.ID
            VALUES (GETDATE(), ?)
        ", [$kundeID]);
        if (!$stmt) {
            throw new Exception("Warenkorb einfügen fehlgeschlagen: " . print_r(sqlsrv_errors(), true));
        }

        $warenkorbRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $warenkorbID = $warenkorbRow['ID'];

        $stmt = sqlsrv_query($conn, "
            SELECT ProduktID, Confidence 
            FROM Group_Product_Rules 
            WHERE GruppeID = ?
        ", [$gruppeID]);

        if (!$stmt) {
            throw new Exception("Group_Product_Rules Abfrage fehlgeschlagen: " . print_r(sqlsrv_errors(), true));
        }

        $produktAffinitaet = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $produktAffinitaet[$row['ProduktID']] = $row['Confidence'];
        }

        $kaufteProdukte = [];
        foreach ($produktAffinitaet as $pid => $conf) {
            if (rand() / getrandmax() < $conf) {
                $kaufteProdukte[] = $pid;
            }
        }

        foreach ($kaufteProdukte as $p1) {
            $stmt = sqlsrv_query($conn, "
                SELECT Produkt2ID, Wahrscheinlichkeit 
                FROM Product_Combinations 
                WHERE GruppeID = ? AND Produkt1ID = ?
            ", [$gruppeID, $p1]);

            if (!$stmt) continue;

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                if (rand() / getrandmax() < $row['Wahrscheinlichkeit']) {
                    $kaufteProdukte[] = $row['Produkt2ID'];
                }
            }
        }

        $kaufteProdukte = array_unique($kaufteProdukte);

        foreach ($kaufteProdukte as $produktID) {
            $menge = rand(1, 3);
            $stmt = sqlsrv_query($conn, "
                INSERT INTO Bestellungen (ProduktID, K_GruppeID, WarenkorbID, ProduktAnzahl)
                VALUES (?, ?, ?, ?)
            ", [$produktID, $gruppeID, $warenkorbID, $menge]);

            if (!$stmt) {
                throw new Exception("Bestellung einfügen fehlgeschlagen: " . print_r(sqlsrv_errors(), true));
            }
        }
    }

    sqlsrv_commit($conn);
    echo json_encode(['success' => true, 'message' => 'Erfolgreich viele Testkunden mit realistischen Kombinationen angelegt!']);
} catch (Exception $e) {
    sqlsrv_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
}
?>
