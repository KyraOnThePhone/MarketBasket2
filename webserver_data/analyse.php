<?php
require 'dbshop.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);


$gruppenQuery = sqlsrv_query($conn, "SELECT ID, Name FROM Gruppen");
$gruppen = [];
while ($row = sqlsrv_fetch_array($gruppenQuery, SQLSRV_FETCH_ASSOC)) {
    $gruppen[$row['ID']] = $row['Name'];
}

$produkteQuery = sqlsrv_query($conn, "SELECT ID, Name FROM Produkte");
$produktNamen = [];
while ($row = sqlsrv_fetch_array($produkteQuery, SQLSRV_FETCH_ASSOC)) {
    $produktNamen[$row['ID']] = $row['Name'];
}


$analyse = [];

foreach ($gruppen as $gruppenID => $gruppenName) {

    $stmt = sqlsrv_query($conn, "
        SELECT ProduktID, SUM(ProduktAnzahl) AS GesamtAnzahl
        FROM Bestellungen
        WHERE K_GruppeID = ?
        GROUP BY ProduktID
    ", [$gruppenID]);

    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => "Fehler bei der Abfrage für Gruppe $gruppenName",
            'details' => sqlsrv_errors()
        ]);
        exit;
    }

    $gesamt = 0;
    $produkte = [];


    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $produktID = $row['ProduktID'];
        $anzahl = $row['GesamtAnzahl'];
        $produkte[$produktID] = $anzahl;
        $gesamt += $anzahl;
    }


    $prozentListe = [];
    foreach ($produkte as $produktID => $anzahl) {
        $prozent = $gesamt > 0 ? round(($anzahl / $gesamt) * 100, 2) : 0;
        $prozentListe[] = [
            'ProduktID' => $produktID,
            'Produktname' => $produktNamen[$produktID] ?? 'Unbekannt',
            'Prozent' => $prozent
        ];
    }


    $analyse[] = [
        'GruppeID' => $gruppenID,
        'Gruppenname' => $gruppenName,
        'Produkte' => $prozentListe
    ];
}


header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'data' => $analyse
], JSON_PRETTY_PRINT);
