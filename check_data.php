<?php
require_once 'admin/includes/functions.php';
require_once 'admin/models/GeneralInfo.php';

$database = new Database();
$db = $database->getConnection();

echo "🔍 Checking General Info Data\n";
echo "=============================\n\n";

// Get all data
$query = 'SELECT id, title, type, is_active, expiry_date FROM general_info ORDER BY id';
$stmt = $db->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total records: " . count($results) . "\n\n";

$generalInfo = new GeneralInfo($db);

foreach ($results as $row) {
    echo "ID: " . $row['id'] . "\n";
    echo "Title: " . $row['title'] . "\n";
    echo "Type: " . $row['type'] . " (" . $generalInfo->getTypeName($row['type']) . ")\n";
    echo "Active: " . ($row['is_active'] ? 'Yes' : 'No') . "\n";
    echo "Expiry: " . ($row['expiry_date'] ?? 'No expiry') . "\n";
    
    if ($row['expiry_date']) {
        $expired = $generalInfo->isExpired($row['expiry_date']);
        echo "Expired: " . ($expired ? 'Yes' : 'No') . "\n";
    }
    
    echo "---\n";
}

// Test getByType method
echo "\nTesting getByType method:\n";
$pengumuman = $generalInfo->getByType('pengumuman');
$kalender = $generalInfo->getByType('kalender');
$prosedur = $generalInfo->getByType('prosedur');
$dokumen = $generalInfo->getByType('dokumen');

echo "Pengumuman: " . count($pengumuman) . " items\n";
echo "Kalender: " . count($kalender) . " items\n";
echo "Prosedur: " . count($prosedur) . " items\n";
echo "Dokumen: " . count($dokumen) . " items\n";

// Check why some are empty
if (count($pengumuman) == 0) {
    echo "\nDebugging Pengumuman:\n";
    $query = "SELECT * FROM general_info WHERE type = 'pengumuman' AND is_active = 1 AND (expiry_date IS NULL OR expiry_date >= CURDATE())";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $debug = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Raw query result: " . count($debug) . " items\n";
}
?>
