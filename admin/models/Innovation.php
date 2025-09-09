<?php
class Innovation {
    private $conn;
    private $table_name = "innovations";

    public $id;
    public $title;
    public $description;
    public $category;
    public $implementation_year;
    public $benefits;
    public $features;
    public $image;
    public $video_url;
    public $is_featured;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all innovations with pagination
    public function getAll($limit = 10, $offset = 0, $search = '', $category = '', $year = '') {
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($category)) {
            $query .= " AND category = ?";
            $params[] = $category;
        }
        
        if (!empty($year)) {
            $query .= " AND implementation_year = ?";
            $params[] = $year;
        }
        
        $query .= " ORDER BY is_featured DESC, implementation_year DESC, created_at DESC LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count total records for pagination
    public function count($search = '', $category = '', $year = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($category)) {
            $query .= " AND category = ?";
            $params[] = $category;
        }
        
        if (!empty($year)) {
            $query .= " AND implementation_year = ?";
            $params[] = $year;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Get single innovation by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get active innovations for frontend
    public function getActive($limit = 10, $offset = 0, $category = '', $year = '') {
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1";
        $params = [];
        
        if (!empty($category)) {
            $query .= " AND category = ?";
            $params[] = $category;
        }
        
        if (!empty($year)) {
            $query .= " AND implementation_year = ?";
            $params[] = $year;
        }
        
        $query .= " ORDER BY is_featured DESC, implementation_year DESC, created_at DESC LIMIT $limit OFFSET $offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get featured innovations
    public function getFeatured($limit = 6) {
        $limit = (int)$limit;
        
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_active = 1 AND is_featured = 1 
                  ORDER BY implementation_year DESC, created_at DESC 
                  LIMIT $limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create new innovation
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, description, category, implementation_year, benefits, features, image, video_url, is_featured, is_active, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $result = $stmt->execute([
            $this->title,
            $this->description,
            $this->category,
            $this->implementation_year,
            $this->benefits,
            $this->features,
            $this->image,
            $this->video_url,
            $this->is_featured ? 1 : 0,
            $this->is_active ? 1 : 0
        ]);
        
        if ($result) {
            $this->id = $this->conn->lastInsertId();
        }
        
        return $result;
    }

    // Update existing innovation
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = ?, description = ?, category = ?, implementation_year = ?, 
                      benefits = ?, features = ?, image = ?, video_url = ?, 
                      is_featured = ?, is_active = ?, updated_at = NOW()
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $this->title,
            $this->description,
            $this->category,
            $this->implementation_year,
            $this->benefits,
            $this->features,
            $this->image,
            $this->video_url,
            $this->is_featured ? 1 : 0,
            $this->is_active ? 1 : 0,
            $this->id
        ]);
    }

    // Delete innovation
    public function delete() {
        // Delete associated image file if exists
        if ($this->image && file_exists("uploads/innovations/" . $this->image)) {
            unlink("uploads/innovations/" . $this->image);
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }

    // Get innovations by category
    public function getByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE category = ? AND is_active = 1 
                  ORDER BY is_featured DESC, implementation_year DESC, created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get innovations by year
    public function getByYear($year) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE implementation_year = ? AND is_active = 1 
                  ORDER BY is_featured DESC, created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get statistics
    public function getStats() {
        $stats = [];
        
        // Total innovations
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Featured count
        $query = "SELECT COUNT(*) as featured FROM " . $this->table_name . " WHERE is_active = 1 AND is_featured = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['featured'] = $stmt->fetch(PDO::FETCH_ASSOC)['featured'];
        
        // By category
        $query = "SELECT category, COUNT(*) as count FROM " . $this->table_name . " WHERE is_active = 1 GROUP BY category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $category_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $stats['by_category'] = $category_stats;
        
        // By year
        $query = "SELECT implementation_year, COUNT(*) as count FROM " . $this->table_name . " WHERE is_active = 1 GROUP BY implementation_year ORDER BY implementation_year DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $year_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $stats['by_year'] = $year_stats;
        
        return $stats;
    }

    // Validate innovation data
    public function validate($data) {
        $errors = [];
        
        // Required fields
        if (empty($data['title'])) {
            $errors[] = 'Judul inovasi harus diisi';
        }
        
        if (empty($data['description'])) {
            $errors[] = 'Deskripsi inovasi harus diisi';
        }
        
        if (empty($data['category'])) {
            $errors[] = 'Kategori inovasi harus dipilih';
        }
        
        if (empty($data['implementation_year'])) {
            $errors[] = 'Tahun implementasi harus diisi';
        }
        
        // Validate category
        $allowed_categories = ['teknologi', 'metode', 'kurikulum', 'fasilitas'];
        if (!empty($data['category']) && !in_array($data['category'], $allowed_categories)) {
            $errors[] = 'Kategori inovasi tidak valid';
        }
        
        // Validate year
        $current_year = (int)date('Y');
        if (!empty($data['implementation_year'])) {
            $year = (int)$data['implementation_year'];
            if ($year < 2000 || $year > ($current_year + 5)) {
                $errors[] = 'Tahun implementasi tidak valid (2000 - ' . ($current_year + 5) . ')';
            }
        }
        
        // Validate benefits and features (can be either text format or JSON)
        if (!empty($data['benefits'])) {
            if (is_string($data['benefits'])) {
                // Try to decode as JSON first
                $benefits = json_decode($data['benefits'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // If not valid JSON, assume it's text format (one per line)
                    // This is valid input from the form, so no error
                    $lines = explode("\n", trim($data['benefits']));
                    $filtered_lines = array_filter(array_map('trim', $lines));
                    if (empty($filtered_lines)) {
                        $errors[] = 'Manfaat harus diisi dengan minimal satu item';
                    }
                }
            }
        }
        
        if (!empty($data['features'])) {
            if (is_string($data['features'])) {
                // Try to decode as JSON first
                $features = json_decode($data['features'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // If not valid JSON, assume it's text format (one per line)
                    // This is valid input from the form, so no error
                    $lines = explode("\n", trim($data['features']));
                    $filtered_lines = array_filter(array_map('trim', $lines));
                    if (empty($filtered_lines)) {
                        $errors[] = 'Fitur harus diisi dengan minimal satu item';
                    }
                }
            }
        }
        
        // Validate video URL
        if (!empty($data['video_url'])) {
            if (!filter_var($data['video_url'], FILTER_VALIDATE_URL)) {
                $errors[] = 'URL video tidak valid';
            }
        }
        
        return $errors;
    }

    // Helper function to get category display name
    public function getCategoryName($category) {
        $names = [
            'teknologi' => 'Teknologi Pembelajaran',
            'metode' => 'Metode Pembelajaran',
            'kurikulum' => 'Inovasi Kurikulum',
            'fasilitas' => 'Fasilitas & Infrastruktur'
        ];
        
        return $names[$category] ?? $category;
    }

    // Helper function to get category icon
    public function getCategoryIcon($category) {
        $icons = [
            'teknologi' => 'fas fa-laptop',
            'metode' => 'fas fa-chalkboard-teacher',
            'kurikulum' => 'fas fa-book-open',
            'fasilitas' => 'fas fa-building'
        ];
        
        return $icons[$category] ?? 'fas fa-lightbulb';
    }

    // Helper function to get category color
    public function getCategoryColor($category) {
        $colors = [
            'teknologi' => 'primary',
            'metode' => 'success',
            'kurikulum' => 'warning',
            'fasilitas' => 'info'
        ];
        
        return $colors[$category] ?? 'secondary';
    }

    // Helper function to format JSON arrays for display
    public function formatJsonArray($jsonString) {
        if (empty($jsonString)) return [];
        
        if (is_array($jsonString)) return $jsonString;
        
        $decoded = json_decode($jsonString, true);
        return is_array($decoded) ? $decoded : [];
    }

    // Helper function to check if innovation is recent (within last 2 years)
    public function isRecent($implementation_year) {
        $current_year = (int)date('Y');
        return ($current_year - (int)$implementation_year) <= 2;
    }

    // Get available years for filtering
    public function getAvailableYears() {
        $query = "SELECT DISTINCT implementation_year FROM " . $this->table_name . " 
                  WHERE is_active = 1 
                  ORDER BY implementation_year DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Search innovations
    public function search($keyword, $limit = 20) {
        $limit = (int)$limit;
        
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE is_active = 1 
                  AND (title LIKE ? OR description LIKE ? OR benefits LIKE ? OR features LIKE ?)
                  ORDER BY is_featured DESC, 
                    CASE 
                        WHEN title LIKE ? THEN 1
                        WHEN description LIKE ? THEN 2
                        ELSE 3
                    END,
                    implementation_year DESC
                  LIMIT $limit";
        
        $search_term = "%$keyword%";
        $title_priority = "%$keyword%";
        $desc_priority = "%$keyword%";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$search_term, $search_term, $search_term, $search_term, $title_priority, $desc_priority]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Toggle featured status
    public function toggleFeatured($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_featured = NOT is_featured, updated_at = NOW() 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Toggle active status
    public function toggleActive($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_active = NOT is_active, updated_at = NOW() 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
