<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Only POST requests are accepted.'
    ]);
    exit;
}

try {
    require_once '../admin/config/database.php';
    require_once '../admin/models/PpdbRegistration.php';

    // Get database connection
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        throw new Exception('Database connection failed');
    }

    // Initialize PpdbRegistration model
    $ppdb = new PpdbRegistration($db);

    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // If JSON decoding failed, try to get from $_POST
    if (!$input) {
        $input = $_POST;
    }

    // Validate required fields
    $required_fields = ['child_name', 'dob', 'gender', 'parent_name', 'email', 'parent_phone', 'address'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            $errors[] = 'Field ' . $field . ' harus diisi';
        }
    }

    // Validate email format
    if (!empty($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak valid',
            'errors' => $errors
        ]);
        exit;
    }

    // Prepare data for model
    $data = [
        'child_name' => $input['child_name'],
        'dob' => $input['dob'],
        'gender' => $input['gender'],
        'previous_school' => $input['previous_school'] ?? '',
        'parent_name' => $input['parent_name'],
        'parent_phone' => $input['parent_phone'],
        'email' => $input['email'],
        'address' => $input['address']
    ];

    // Create registration
    $result = $ppdb->create($data);

    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Pendaftaran berhasil',
            'data' => [
                'registration_number' => $result['registration_number'],
                'child_name' => $data['child_name'],
                'parent_phone' => $data['parent_phone']
            ]
        ]);
    } else {
        throw new Exception($result['message']);
    }

} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    ]);
}
?>