<?php
class Academic {
    private $conn;
    private $table_name = "academic_programs";

    public $id;
    public $title;
    public $description;
    public $grade_level;
    public $curriculum_type;
    public $subjects;
    public $learning_methods;
    public $assessment_methods;
    public $image;
    public $is_active;
    public $sort_order;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all academic programs with pagination
    public function getAll($limit = 10, $offset = 0, $search = '', $grade_level = '', $curriculum_type = '') {
        // Ensure limit and offset are integers
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($grade_level)) {
            $query .= " AND grade_level = ?";
            $params[] = $grade_level;
        }
        
        if (!empty($curriculum_type)) {
            $query .= " AND curriculum_type = ?";
            $params[] = $curriculum_type;
        }
        
        $query .= " ORDER BY sort_order ASC, created_at DESC LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count total records for pagination
    public function count($search = '', $grade_level = '', $curriculum_type = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($grade_level)) {
            $query .= " AND grade_level = ?";
            $params[] = $grade_level;
        }
        
        if (!empty($curriculum_type)) {
            $query .= " AND curriculum_type = ?";
            $params[] = $curriculum_type;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Get single program by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get active programs for frontend
    public function getActive($limit = 10, $offset = 0, $grade_level = '', $curriculum_type = '') {
        // Ensure limit and offset are integers
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1";
        $params = [];
        
        if (!empty($grade_level)) {
            $query .= " AND grade_level = ?";
            $params[] = $grade_level;
        }
        
        if (!empty($curriculum_type)) {
            $query .= " AND curriculum_type = ?";
            $params[] = $curriculum_type;
        }
        
        $query .= " ORDER BY sort_order ASC, created_at DESC LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create new program
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, description, grade_level, curriculum_type, subjects, learning_methods, assessment_methods, image, is_active, sort_order, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $result = $stmt->execute([
            $this->title,
            $this->description,
            $this->grade_level,
            $this->curriculum_type,
            $this->subjects,
            $this->learning_methods,
            $this->assessment_methods,
            $this->image,
            $this->is_active ? 1 : 0,
            $this->sort_order
        ]);
        
        if ($result) {
            $this->id = $this->conn->lastInsertId();
        }
        
        return $result;
    }

    // Update existing program
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = ?, description = ?, grade_level = ?, curriculum_type = ?, 
                      subjects = ?, learning_methods = ?, assessment_methods = ?, image = ?, 
                      is_active = ?, sort_order = ?, updated_at = NOW()
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $this->title,
            $this->description,
            $this->grade_level,
            $this->curriculum_type,
            $this->subjects,
            $this->learning_methods,
            $this->assessment_methods,
            $this->image,
            $this->is_active ? 1 : 0,
            $this->sort_order,
            $this->id
        ]);
    }

    // Delete program
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }

    // Get programs by grade level
    public function getByGradeLevel($grade_level) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE grade_level = ? AND is_active = 1 
                  ORDER BY sort_order ASC, created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$grade_level]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get programs by curriculum type
    public function getByCurriculumType($curriculum_type) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE curriculum_type = ? AND is_active = 1 
                  ORDER BY sort_order ASC, created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$curriculum_type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Validate academic program data
    public function validate($data) {
        $errors = [];
        
        // Required fields
        if (empty($data['title'])) {
            $errors[] = 'Judul program harus diisi';
        }
        
        if (empty($data['description'])) {
            $errors[] = 'Deskripsi program harus diisi';
        }
        
        if (empty($data['grade_level'])) {
            $errors[] = 'Tingkat kelas harus dipilih';
        }
        
        // Validate grade level
        $allowed_grades = ['1', '2', '3', '4', '5', '6', 'semua'];
        if (!empty($data['grade_level']) && !in_array($data['grade_level'], $allowed_grades)) {
            $errors[] = 'Tingkat kelas tidak valid';
        }
        
        // Validate curriculum type
        $allowed_curriculum = ['nasional', 'internasional', 'muatan_lokal'];
        if (!empty($data['curriculum_type']) && !in_array($data['curriculum_type'], $allowed_curriculum)) {
            $errors[] = 'Jenis kurikulum tidak valid';
        }
        
        // Validate JSON fields
        if (!empty($data['subjects'])) {
            $subjects = json_decode($data['subjects'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = 'Format mata pelajaran tidak valid (harus berupa JSON)';
            }
        }
        
        if (!empty($data['learning_methods'])) {
            $methods = json_decode($data['learning_methods'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = 'Format metode pembelajaran tidak valid (harus berupa JSON)';
            }
        }
        
        if (!empty($data['assessment_methods'])) {
            $assessments = json_decode($data['assessment_methods'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = 'Format metode penilaian tidak valid (harus berupa JSON)';
            }
        }
        
        return $errors;
    }

    // Helper function to format JSON arrays for display
    public function formatJsonArray($jsonString) {
        if (empty($jsonString)) return [];
        
        $decoded = json_decode($jsonString, true);
        return is_array($decoded) ? $decoded : [];
    }

    // Helper function to get grade level display name
    public function getGradeLevelName($grade_level) {
        $names = [
            '1' => 'Kelas 1',
            '2' => 'Kelas 2',
            '3' => 'Kelas 3',
            '4' => 'Kelas 4',
            '5' => 'Kelas 5',
            '6' => 'Kelas 6',
            'semua' => 'Semua Kelas'
        ];
        
        return $names[$grade_level] ?? $grade_level;
    }

    // Helper function to get curriculum type display name
    public function getCurriculumTypeName($curriculum_type) {
        $names = [
            'nasional' => 'Kurikulum Nasional',
            'internasional' => 'Kurikulum Internasional',
            'muatan_lokal' => 'Muatan Lokal'
        ];
        
        return $names[$curriculum_type] ?? $curriculum_type;
    }
}
?>
