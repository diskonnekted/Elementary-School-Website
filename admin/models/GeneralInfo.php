<?php
class GeneralInfo {
    private $conn;
    private $table_name = "general_info";

    public $id;
    public $title;
    public $content;
    public $type;
    public $priority;
    public $expiry_date;
    public $attachment;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all general info with pagination
    public function getAll($limit = 10, $offset = 0, $search = '', $type = '', $priority = '') {
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (title LIKE ? OR content LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($type)) {
            $query .= " AND type = ?";
            $params[] = $type;
        }
        
        if (!empty($priority)) {
            $query .= " AND priority = ?";
            $params[] = $priority;
        }
        
        $query .= " ORDER BY 
                    CASE priority 
                        WHEN 'tinggi' THEN 1 
                        WHEN 'sedang' THEN 2 
                        WHEN 'rendah' THEN 3 
                    END,
                    created_at DESC LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count total records for pagination
    public function count($search = '', $type = '', $priority = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (title LIKE ? OR content LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($type)) {
            $query .= " AND type = ?";
            $params[] = $type;
        }
        
        if (!empty($priority)) {
            $query .= " AND priority = ?";
            $params[] = $priority;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Get single info by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get active info for frontend
    public function getActive($limit = 10, $offset = 0, $type = '', $priority = '') {
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_active = 1 AND (expiry_date IS NULL OR expiry_date >= CURDATE())";
        $params = [];
        
        if (!empty($type)) {
            $query .= " AND type = ?";
            $params[] = $type;
        }
        
        if (!empty($priority)) {
            $query .= " AND priority = ?";
            $params[] = $priority;
        }
        
        $query .= " ORDER BY 
                    CASE priority 
                        WHEN 'tinggi' THEN 1 
                        WHEN 'sedang' THEN 2 
                        WHEN 'rendah' THEN 3 
                    END,
                    created_at DESC LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create new info
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, content, type, priority, expiry_date, attachment, is_active, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $result = $stmt->execute([
            $this->title,
            $this->content,
            $this->type,
            $this->priority,
            $this->expiry_date,
            $this->attachment,
            $this->is_active ? 1 : 0
        ]);
        
        if ($result) {
            $this->id = $this->conn->lastInsertId();
        }
        
        return $result;
    }

    // Update existing info
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = ?, content = ?, type = ?, priority = ?, 
                      expiry_date = ?, attachment = ?, is_active = ?, updated_at = NOW()
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $this->title,
            $this->content,
            $this->type,
            $this->priority,
            $this->expiry_date,
            $this->attachment,
            $this->is_active ? 1 : 0,
            $this->id
        ]);
    }

    // Delete info
    public function delete() {
        // Delete associated attachment file if exists
        if ($this->attachment && file_exists("uploads/attachments/" . $this->attachment)) {
            unlink("uploads/attachments/" . $this->attachment);
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }

    // Get info by type
    public function getByType($type) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE type = ? AND is_active = 1 AND (expiry_date IS NULL OR expiry_date >= CURDATE())
                  ORDER BY 
                    CASE priority 
                        WHEN 'tinggi' THEN 1 
                        WHEN 'sedang' THEN 2 
                        WHEN 'rendah' THEN 3 
                    END,
                    created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get expired info
    public function getExpired() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE expiry_date IS NOT NULL AND expiry_date < CURDATE() AND is_active = 1
                  ORDER BY expiry_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Validate general info data
    public function validate($data) {
        $errors = [];
        
        // Required fields
        if (empty($data['title'])) {
            $errors[] = 'Judul harus diisi';
        }
        
        if (empty($data['content'])) {
            $errors[] = 'Konten harus diisi';
        }
        
        if (empty($data['type'])) {
            $errors[] = 'Tipe informasi harus dipilih';
        }
        
        // Validate type
        $allowed_types = ['pengumuman', 'kalender', 'prosedur', 'dokumen'];
        if (!empty($data['type']) && !in_array($data['type'], $allowed_types)) {
            $errors[] = 'Tipe informasi tidak valid';
        }
        
        // Validate priority
        $allowed_priorities = ['tinggi', 'sedang', 'rendah'];
        if (!empty($data['priority']) && !in_array($data['priority'], $allowed_priorities)) {
            $errors[] = 'Prioritas tidak valid';
        }
        
        // Validate expiry date
        if (!empty($data['expiry_date'])) {
            $date = DateTime::createFromFormat('Y-m-d', $data['expiry_date']);
            if (!$date || $date->format('Y-m-d') !== $data['expiry_date']) {
                $errors[] = 'Format tanggal kedaluwarsa tidak valid';
            }
        }
        
        return $errors;
    }

    // Helper function to get type display name
    public function getTypeName($type) {
        $names = [
            'pengumuman' => 'Pengumuman',
            'kalender' => 'Kalender Akademik',
            'prosedur' => 'Prosedur & SOP',
            'dokumen' => 'Dokumen Penting'
        ];
        
        return $names[$type] ?? $type;
    }

    // Helper function to get priority display name
    public function getPriorityName($priority) {
        $names = [
            'tinggi' => 'Tinggi',
            'sedang' => 'Sedang',
            'rendah' => 'Rendah'
        ];
        
        return $names[$priority] ?? $priority;
    }

    // Helper function to get priority badge class
    public function getPriorityBadgeClass($priority) {
        $classes = [
            'tinggi' => 'bg-red-100 text-red-800',
            'sedang' => 'bg-yellow-100 text-yellow-800',
            'rendah' => 'bg-green-100 text-green-800'
        ];
        
        return $classes[$priority] ?? 'bg-gray-100 text-gray-800';
    }

    // Helper function to get type icon
    public function getTypeIcon($type) {
        $icons = [
            'pengumuman' => 'fas fa-bullhorn',
            'kalender' => 'fas fa-calendar-alt',
            'prosedur' => 'fas fa-list-ol',
            'dokumen' => 'fas fa-file-alt'
        ];
        
        return $icons[$type] ?? 'fas fa-info-circle';
    }

    // Check if info is expired
    public function isExpired($expiry_date) {
        if (empty($expiry_date)) return false;
        
        $today = new DateTime();
        $expiry = new DateTime($expiry_date);
        
        return $expiry < $today;
    }

    // Get info count by type
    public function countByType() {
        $query = "SELECT type, COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  WHERE is_active = 1 
                  GROUP BY type";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    // Get info count by priority
    public function countByPriority() {
        $query = "SELECT priority, COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  WHERE is_active = 1 
                  GROUP BY priority";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
?>
