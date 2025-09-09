<?php
class ContactMessage {
    private $conn;
    private $table_name = "contact_messages";

    public function __construct($db) {
        $this->conn = $db;
        $this->createTable();
    }

    private function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL,
            phone VARCHAR(20),
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            status ENUM('unread', 'read', 'replied', 'archived') DEFAULT 'unread',
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            replied_at TIMESTAMP NULL,
            replied_by VARCHAR(100),
            admin_notes TEXT,
            INDEX idx_status (status),
            INDEX idx_created (created_at),
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        try {
            $this->conn->exec($query);
        } catch(PDOException $e) {
            error_log("Error creating contact_messages table: " . $e->getMessage());
        }
    }

    public function create($name, $email, $phone, $subject, $message, $ip_address = null, $user_agent = null) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, email, phone, subject, message, ip_address, user_agent) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->conn->prepare($query);
            
            // Sanitize input
            $name = htmlspecialchars(strip_tags(trim($name)));
            $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
            $phone = htmlspecialchars(strip_tags(trim($phone)));
            $subject = htmlspecialchars(strip_tags(trim($subject)));
            $message = htmlspecialchars(strip_tags(trim($message)));
            
            // Validate required fields
            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                throw new Exception("Required fields are missing");
            }
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }
            
            $stmt->execute([
                $name,
                $email,
                $phone,
                $subject,
                $message,
                $ip_address,
                $user_agent
            ]);

            return [
                'success' => true,
                'id' => $this->conn->lastInsertId(),
                'message' => 'Message sent successfully'
            ];

        } catch(Exception $e) {
            error_log("Error creating contact message: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getAll($status = '', $limit = 50, $offset = 0, $search = '') {
        $query = "SELECT * FROM " . $this->table_name;
        $conditions = [];
        $params = [];

        if (!empty($status)) {
            $conditions[] = "status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $conditions[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
            $searchParam = "%$search%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Add LIMIT and OFFSET directly to the query instead of using parameters
        $limit = (int)$limit;
        $offset = (int)$offset;
        $query .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting contact messages: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting contact message by ID: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id, $status, $admin_user = null, $admin_notes = null) {
        $valid_statuses = ['unread', 'read', 'replied', 'archived'];
        
        if (!in_array($status, $valid_statuses)) {
            return ['success' => false, 'message' => 'Invalid status'];
        }

        $query = "UPDATE " . $this->table_name . " SET status = ?, updated_at = CURRENT_TIMESTAMP";
        $params = [$status];

        if ($status === 'replied' && $admin_user) {
            $query .= ", replied_at = CURRENT_TIMESTAMP, replied_by = ?";
            $params[] = $admin_user;
        }

        if ($admin_notes) {
            $query .= ", admin_notes = ?";
            $params[] = htmlspecialchars(strip_tags(trim($admin_notes)));
        }

        $query .= " WHERE id = ?";
        $params[] = $id;

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Status updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Message not found'];
            }
        } catch(PDOException $e) {
            error_log("Error updating message status: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Message deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Message not found'];
            }
        } catch(PDOException $e) {
            error_log("Error deleting contact message: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'unread' THEN 1 ELSE 0 END) as unread,
                    SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as `read`,
                    SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied,
                    SUM(CASE WHEN status = 'archived' THEN 1 ELSE 0 END) as archived
                  FROM " . $this->table_name;

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting message stats: " . $e->getMessage());
            return [
                'total' => 0,
                'unread' => 0, 
                'read' => 0,
                'replied' => 0,
                'archived' => 0
            ];
        }
    }

    public function getRecentMessages($limit = 5) {
        // Sanitize limit to prevent SQL injection
        $limit = (int)$limit;
        $query = "SELECT id, name, email, subject, status, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC 
                  LIMIT $limit";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting recent messages: " . $e->getMessage());
            return [];
        }
    }

    public function markAsRead($id) {
        return $this->updateStatus($id, 'read');
    }

    public function getStatusBadge($status) {
        $badges = [
            'unread' => '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Belum Dibaca</span>',
            'read' => '<span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Sudah Dibaca</span>',
            'replied' => '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Sudah Dibalas</span>',
            'archived' => '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Diarsipkan</span>'
        ];

        return $badges[$status] ?? '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Unknown</span>';
    }

    public function formatTimeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'baru saja';
        if ($time < 3600) return floor($time/60) . ' menit yang lalu';
        if ($time < 86400) return floor($time/3600) . ' jam yang lalu';
        if ($time < 2592000) return floor($time/86400) . ' hari yang lalu';
        if ($time < 31536000) return floor($time/2592000) . ' bulan yang lalu';
        return floor($time/31536000) . ' tahun yang lalu';
    }
}
?>
