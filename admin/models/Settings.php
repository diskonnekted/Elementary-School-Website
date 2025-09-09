<?php
class Settings {
    private $conn;
    private $table_name = "school_settings";

    public $id;
    public $school_name;
    public $school_motto;
    public $school_description;
    public $school_logo;
    public $school_address;
    public $school_phone;
    public $school_email;
    public $school_website;
    public $school_latitude;
    public $school_longitude;
    public $principal_name;
    public $principal_photo;
    public $established_year;
    public $npsn;
    public $accreditation;
    public $facebook_url;
    public $instagram_url;
    public $youtube_url;
    public $twitter_url;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all settings
    public function getSettings() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update or create settings
    public function saveSettings($data) {
        // Check if settings exist
        $existing = $this->getSettings();
        
        if ($existing) {
            // Update existing settings
            return $this->updateSettings($data, $existing['id']);
        } else {
            // Create new settings
            return $this->createSettings($data);
        }
    }

    // Create new settings
    private function createSettings($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (school_name, school_motto, school_description, school_logo, 
                   school_address, school_phone, school_email, school_website,
                   school_latitude, school_longitude, principal_name, principal_photo,
                   established_year, npsn, accreditation,
                   facebook_url, instagram_url, youtube_url, twitter_url, updated_at)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['school_name'] ?? '',
            $data['school_motto'] ?? '',
            $data['school_description'] ?? '',
            $data['school_logo'] ?? '',
            $data['school_address'] ?? '',
            $data['school_phone'] ?? '',
            $data['school_email'] ?? '',
            $data['school_website'] ?? '',
            $data['school_latitude'] ?? '',
            $data['school_longitude'] ?? '',
            $data['principal_name'] ?? '',
            $data['principal_photo'] ?? '',
            $data['established_year'] ?? '',
            $data['npsn'] ?? '',
            $data['accreditation'] ?? '',
            $data['facebook_url'] ?? '',
            $data['instagram_url'] ?? '',
            $data['youtube_url'] ?? '',
            $data['twitter_url'] ?? ''
        ]);
    }

    // Update existing settings
    private function updateSettings($data, $id) {
        $query = "UPDATE " . $this->table_name . " SET
                  school_name = ?, school_motto = ?, school_description = ?, school_logo = ?,
                  school_address = ?, school_phone = ?, school_email = ?, school_website = ?,
                  school_latitude = ?, school_longitude = ?, principal_name = ?, principal_photo = ?,
                  established_year = ?, npsn = ?, accreditation = ?,
                  facebook_url = ?, instagram_url = ?, youtube_url = ?, twitter_url = ?,
                  updated_at = NOW()
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['school_name'] ?? '',
            $data['school_motto'] ?? '',
            $data['school_description'] ?? '',
            $data['school_logo'] ?? '',
            $data['school_address'] ?? '',
            $data['school_phone'] ?? '',
            $data['school_email'] ?? '',
            $data['school_website'] ?? '',
            $data['school_latitude'] ?? '',
            $data['school_longitude'] ?? '',
            $data['principal_name'] ?? '',
            $data['principal_photo'] ?? '',
            $data['established_year'] ?? '',
            $data['npsn'] ?? '',
            $data['accreditation'] ?? '',
            $data['facebook_url'] ?? '',
            $data['instagram_url'] ?? '',
            $data['youtube_url'] ?? '',
            $data['twitter_url'] ?? '',
            $id
        ]);
    }

    // Get specific setting value
    public function getSetting($key) {
        $settings = $this->getSettings();
        return $settings[$key] ?? null;
    }

    // Helper function to get social media links
    public function getSocialMediaLinks() {
        $settings = $this->getSettings();
        if (!$settings) return [];

        $links = [];
        if (!empty($settings['facebook_url'])) {
            $links['facebook'] = $settings['facebook_url'];
        }
        if (!empty($settings['instagram_url'])) {
            $links['instagram'] = $settings['instagram_url'];
        }
        if (!empty($settings['youtube_url'])) {
            $links['youtube'] = $settings['youtube_url'];
        }
        if (!empty($settings['twitter_url'])) {
            $links['twitter'] = $settings['twitter_url'];
        }

        return $links;
    }

    // Helper function to get contact information
    public function getContactInfo() {
        $settings = $this->getSettings();
        if (!$settings) return [];

        return [
            'address' => $settings['school_address'] ?? '',
            'phone' => $settings['school_phone'] ?? '',
            'email' => $settings['school_email'] ?? '',
            'website' => $settings['school_website'] ?? ''
        ];
    }

    // Helper function to get map coordinates
    public function getMapCoordinates() {
        $settings = $this->getSettings();
        if (!$settings) return ['lat' => -6.2088, 'lng' => 106.8456]; // Default Jakarta

        return [
            'lat' => floatval($settings['school_latitude']) ?: -6.2088,
            'lng' => floatval($settings['school_longitude']) ?: 106.8456
        ];
    }

    // Validate settings data
    public function validate($data) {
        $errors = [];

        if (empty($data['school_name'])) {
            $errors[] = 'Nama sekolah harus diisi';
        }

        if (!empty($data['school_email']) && !filter_var($data['school_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid';
        }

        if (!empty($data['school_website']) && !filter_var($data['school_website'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Format website tidak valid';
        }

        if (!empty($data['school_latitude']) && !is_numeric($data['school_latitude'])) {
            $errors[] = 'Latitude harus berupa angka';
        }

        if (!empty($data['school_longitude']) && !is_numeric($data['school_longitude'])) {
            $errors[] = 'Longitude harus berupa angka';
        }

        if (!empty($data['established_year']) && (!is_numeric($data['established_year']) || $data['established_year'] < 1900 || $data['established_year'] > date('Y'))) {
            $errors[] = 'Tahun berdiri tidak valid';
        }

        return $errors;
    }
}
?>
