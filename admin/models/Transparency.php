<?php
class Transparency {
    private $conn;
    private $table_name = "transparency";

    public $id;
    public $title;
    public $content;
    public $section_type;
    public $file_attachment;
    public $is_active;
    public $sort_order;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all transparency items
    public function getAll($section_type = '') {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1";
        $params = [];
        
        if (!empty($section_type)) {
            $query .= " AND section_type = ?";
            $params[] = $section_type;
        }
        
        $query .= " ORDER BY sort_order ASC, created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get by section type
    public function getBySection($section_type) {
        return $this->getAll($section_type);
    }

    // Create new transparency item
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, content, section_type, file_attachment, is_active, sort_order, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $result = $stmt->execute([
            $this->title,
            $this->content,
            $this->section_type,
            $this->file_attachment,
            $this->is_active ? 1 : 0,
            $this->sort_order ?: 0
        ]);
        
        if ($result) {
            $this->id = $this->conn->lastInsertId();
        }
        
        return $result;
    }

    // Update transparency item
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = ?, content = ?, section_type = ?, file_attachment = ?, 
                      is_active = ?, sort_order = ?, updated_at = NOW()
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $this->title,
            $this->content,
            $this->section_type,
            $this->file_attachment,
            $this->is_active ? 1 : 0,
            $this->sort_order ?: 0,
            $this->id
        ]);
    }

    // Delete transparency item
    public function delete() {
        // Delete associated file if exists
        if ($this->file_attachment && file_exists("uploads/attachments/" . $this->file_attachment)) {
            unlink("uploads/attachments/" . $this->file_attachment);
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }

    // Toggle active status
    public function toggleActive($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_active = NOT is_active, updated_at = NOW() 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Get available section types
    public function getSectionTypes() {
        return [
            'financial' => 'Laporan Keuangan',
            'budget' => 'Anggaran Sekolah',
            'governance' => 'Tata Kelola',
            'reports' => 'Laporan Berkala',
            'policies' => 'Kebijakan',
            'procurement' => 'Pengadaan',
            'other' => 'Lainnya'
        ];
    }

    // Get section type name
    public function getSectionTypeName($type) {
        $types = $this->getSectionTypes();
        return $types[$type] ?? $type;
    }

    // Validate transparency data
    public function validate($data) {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = 'Judul harus diisi';
        }

        if (empty($data['content'])) {
            $errors[] = 'Konten harus diisi';
        }

        if (empty($data['section_type'])) {
            $errors[] = 'Tipe section harus dipilih';
        }

        $validSections = array_keys($this->getSectionTypes());
        if (!empty($data['section_type']) && !in_array($data['section_type'], $validSections)) {
            $errors[] = 'Tipe section tidak valid';
        }

        if (!empty($data['sort_order']) && !is_numeric($data['sort_order'])) {
            $errors[] = 'Urutan harus berupa angka';
        }

        return $errors;
    }

    // Get statistics
    public function getStats() {
        $stats = [];
        
        // Total items
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Active items
        $query = "SELECT COUNT(*) as active FROM " . $this->table_name . " WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['active'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
        
        // By section
        $query = "SELECT section_type, COUNT(*) as count FROM " . $this->table_name . " 
                  WHERE is_active = 1 GROUP BY section_type";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $section_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $stats['by_section'] = $section_stats;
        
        return $stats;
    }

    // Search transparency items
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_active = 1 
                  AND (title LIKE ? OR content LIKE ?)
                  ORDER BY sort_order ASC, created_at DESC";
        
        $search_term = "%$keyword%";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$search_term, $search_term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get items with files only
    public function getItemsWithFiles() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_active = 1 AND file_attachment IS NOT NULL AND file_attachment != ''
                  ORDER BY sort_order ASC, created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update sort order
    public function updateSortOrder($id, $order) {
        $query = "UPDATE " . $this->table_name . " 
                  SET sort_order = ?, updated_at = NOW() 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$order, $id]);
    }

    // Get latest transparency items
    public function getLatest($limit = 5) {
        $limit = (int)$limit;
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_active = 1 
                  ORDER BY created_at DESC 
                  LIMIT $limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Upload file
    public function uploadFile($file) {
        $uploadDir = 'uploads/attachments/';
        
        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Validate file
        $allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension']);
        
        if (!in_array($extension, $allowedTypes)) {
            return false; // Invalid file type
        }
        
        if ($file['size'] > $maxSize) {
            return false; // File too large
        }
        
        // Generate unique filename
        $fileName = 'transparency_' . time() . '_' . uniqid() . '.' . $extension;
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $fileName;
        } else {
            return false; // Upload failed
        }
    }
    
    // Get statistics for admin dashboard
    public function getStatistics() {
        $query = "SELECT section_type, 
                         COUNT(*) as total, 
                         SUM(is_active) as active
                  FROM {$this->table_name} 
                  GROUP BY section_type 
                  ORDER BY total DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
