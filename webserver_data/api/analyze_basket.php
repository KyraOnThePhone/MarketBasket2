<?php
include '../sessioncheck.php';
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || !in_array('dev', $_SESSION['permissions'])) {
    die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

$serverName = "sql, 1433";
$connectionInfo = array(
    "Database" => "Shop",
    "UID" => "sa",
    "PWD" => "BratwurstIN23!",
    "TrustServerCertificate" => true
);
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn === false) {
    die(json_encode(["success" => false, "error" => print_r(sqlsrv_errors(), true)]));
}

// Fetch all transactions grouped by cart
$sql = "
SELECT w.ID AS WarenkorbID, p.Name AS Produktname 
FROM Bestellungen b
JOIN Warenkorb w ON b.WarenkorbID = w.ID
JOIN Produkte p ON b.ProduktID = p.ID
ORDER BY w.ID";

$stmt = sqlsrv_query($conn, $sql);
if (!$stmt) {
    die(json_encode(["success" => false, "error" => print_r(sqlsrv_errors(), true)]));
}

$transactions = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $transactions[$row['WarenkorbID']][] = $row['Produktname'];
}
sqlsrv_free_stmt($stmt);

if (empty($transactions)) {
    echo json_encode(['success' => false, 'error' => 'No transactions found']);
    exit;
}

// Count single item frequencies
$itemCounts = [];
foreach ($transactions as $basket) {
    foreach ($basket as $item) {
        $itemCounts[$item] = ($itemCounts[$item] ?? 0) + 1;
    }
}

// Count co-occurrences
$pairCounts = [];
foreach ($transactions as $basket) {
    sort($basket); // Ensure consistent ordering
    $uniqueItems = array_unique($basket);
    for ($i = 0; $i < count($uniqueItems); $i++) {
        for ($j = $i + 1; $j < count($uniqueItems); $j++) {
            $key = $uniqueItems[$i] . " + " . $uniqueItems[$j];
            $pairCounts[$key] = ($pairCounts[$key] ?? 0) + 1;
        }
    }
}

$totalTransactions = count($transactions);
$results = [];

foreach ($pairCounts as $pair => $count) {
    list($itemA, $itemB) = explode(" + ", $pair);
    $supportAB = $count / $totalTransactions;
    $confidenceAB = $supportAB / (($itemCounts[$itemA] ?? 1) / $totalTransactions);
    $liftAB = $confidenceAB / (($itemCounts[$itemB] ?? 1) / $totalTransactions);

    $results[] = [
        'rule' => "$itemA → $itemB",
        'support' => round($supportAB, 4),
        'confidence' => round($confidenceAB, 4),
        'lift' => round($liftAB, 4)
    ];
}

echo json_encode(['success' => true, 'rules' => $results]);
?>