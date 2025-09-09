<?php
/**
 * Database Configuration Template
 * 
 * Copy this file to database.php and update with your actual database credentials
 */

class Database {
    // Database credentials - UPDATE THESE VALUES
    private $host = "localhost";          // Database host
    private $db_name = "school_database"; // Database name
    private $username = "your_username";  // Database username
    private $password = "your_password";  // Database password
    private $charset = "utf8mb4";
    
    public $conn;
    
    // Get database connection
    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>
