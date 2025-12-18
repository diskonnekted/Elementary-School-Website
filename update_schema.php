<?php
require_once 'admin/config/database.php';
try {
    $db = (new Database())->getConnection();
    
    // Check if columns exist first to avoid errors
    $stmt = $db->query("DESCRIBE teacher_profiles");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $alter = [];
    if (!in_array('education', $columns)) $alter[] = "ADD COLUMN education TEXT NULL";
    if (!in_array('achievements', $columns)) $alter[] = "ADD COLUMN achievements TEXT NULL";
    if (!in_array('certificates', $columns)) $alter[] = "ADD COLUMN certificates TEXT NULL";
    if (!in_array('training', $columns)) $alter[] = "ADD COLUMN training TEXT NULL";
    
    if (!empty($alter)) {
        $sql = "ALTER TABLE teacher_profiles " . implode(", ", $alter);
        $db->exec($sql);
        echo "Columns added successfully.";
    } else {
        echo "Columns already exist.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>