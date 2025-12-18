<?php
require_once 'admin/config/database.php';
try {
    $db = (new Database())->getConnection();
    $stmt = $db->query('DESCRIBE teacher_profiles');
    echo "COLUMNS: ";
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo $row['Field'] . ",";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>