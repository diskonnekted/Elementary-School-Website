<?php
require_once 'admin/config/database.php';
try {
    $db = (new Database())->getConnection();
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "TABLES: " . implode(", ", $tables) . "\n";
    
    if (in_array('teacher_profiles', $tables)) {
        $stmt = $db->query("DESCRIBE teacher_profiles");
        echo "TEACHER_PROFILES COLUMNS: ";
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            echo $row['Field'] . ",";
        }
    } else {
        echo "teacher_profiles table not found.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>