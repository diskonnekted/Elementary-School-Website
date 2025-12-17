<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../admin/config/database.php';
require_once '../admin/models/Academic.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    $academic = new Academic($db);
    
    $action = $_GET['action'] ?? 'list';
    
    switch ($action) {
        case 'list':
            // Get active programs for frontend
            $page = max(1, $_GET['page'] ?? 1);
            $limit = max(1, min(50, $_GET['limit'] ?? 10)); // Max 50 items per page
            $offset = ($page - 1) * $limit;
            
            $grade_level = $_GET['grade_level'] ?? '';
            $curriculum_type = $_GET['curriculum_type'] ?? '';
            
            // Get programs
            $programs = $academic->getActive($limit, $offset, $grade_level, $curriculum_type);
            
            // Count total for pagination
            $total = $academic->count('', $grade_level, $curriculum_type);
            
            // Format programs data
            $formatted_programs = [];
            foreach ($programs as $program) {
                $formatted_programs[] = [
                    'id' => (int)$program['id'],
                    'title' => $program['title'],
                    'description' => $program['description'],
                    'grade_level' => $program['grade_level'],
                    'grade_level_name' => $academic->getGradeLevelName($program['grade_level']),
                    'curriculum_type' => $program['curriculum_type'],
                    'curriculum_type_name' => $academic->getCurriculumTypeName($program['curriculum_type']),
                    'subjects' => $academic->formatJsonArray($program['subjects']),
                    'learning_methods' => $academic->formatJsonArray($program['learning_methods']),
                    'assessment_methods' => $academic->formatJsonArray($program['assessment_methods']),
                    'image' => $program['image'] ? '/admin/uploads/' . $program['image'] : null,
                    'sort_order' => (int)$program['sort_order'],
                    'created_at' => $program['created_at']
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $formatted_programs,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $limit)
                ]
            ], JSON_PRETTY_PRINT);
            break;
            
        case 'detail':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('ID program tidak ditemukan');
            }
            
            $program = $academic->getById($id);
            if (!$program || !$program['is_active']) {
                throw new Exception('Program tidak ditemukan atau tidak aktif');
            }
            
            $formatted_program = [
                'id' => (int)$program['id'],
                'title' => $program['title'],
                'description' => $program['description'],
                'grade_level' => $program['grade_level'],
                'grade_level_name' => $academic->getGradeLevelName($program['grade_level']),
                'curriculum_type' => $program['curriculum_type'],
                'curriculum_type_name' => $academic->getCurriculumTypeName($program['curriculum_type']),
                'subjects' => $academic->formatJsonArray($program['subjects']),
                'learning_methods' => $academic->formatJsonArray($program['learning_methods']),
                'assessment_methods' => $academic->formatJsonArray($program['assessment_methods']),
                'image' => $program['image'] ? '/admin/uploads/' . $program['image'] : null,
                'sort_order' => (int)$program['sort_order'],
                'created_at' => $program['created_at'],
                'updated_at' => $program['updated_at']
            ];
            
            echo json_encode([
                'success' => true,
                'data' => $formatted_program
            ], JSON_PRETTY_PRINT);
            break;
            
        case 'by_grade':
            $grade_level = $_GET['grade_level'] ?? '';
            if (empty($grade_level)) {
                throw new Exception('Tingkat kelas harus diisi');
            }
            
            $programs = $academic->getByGradeLevel($grade_level);
            
            $formatted_programs = [];
            foreach ($programs as $program) {
                $formatted_programs[] = [
                    'id' => (int)$program['id'],
                    'title' => $program['title'],
                    'description' => $program['description'],
                    'grade_level' => $program['grade_level'],
                    'grade_level_name' => $academic->getGradeLevelName($program['grade_level']),
                    'curriculum_type' => $program['curriculum_type'],
                    'curriculum_type_name' => $academic->getCurriculumTypeName($program['curriculum_type']),
                    'subjects' => $academic->formatJsonArray($program['subjects']),
                    'learning_methods' => $academic->formatJsonArray($program['learning_methods']),
                    'assessment_methods' => $academic->formatJsonArray($program['assessment_methods']),
                    'image' => $program['image'] ? '../admin/' . $program['image'] : null,
                    'sort_order' => (int)$program['sort_order'],
                    'created_at' => $program['created_at']
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $formatted_programs,
                'grade_level' => $grade_level,
                'grade_level_name' => $academic->getGradeLevelName($grade_level),
                'total' => count($formatted_programs)
            ], JSON_PRETTY_PRINT);
            break;
            
        case 'by_curriculum':
            $curriculum_type = $_GET['curriculum_type'] ?? '';
            if (empty($curriculum_type)) {
                throw new Exception('Jenis kurikulum harus diisi');
            }
            
            $programs = $academic->getByCurriculumType($curriculum_type);
            
            $formatted_programs = [];
            foreach ($programs as $program) {
                $formatted_programs[] = [
                    'id' => (int)$program['id'],
                    'title' => $program['title'],
                    'description' => $program['description'],
                    'grade_level' => $program['grade_level'],
                    'grade_level_name' => $academic->getGradeLevelName($program['grade_level']),
                    'curriculum_type' => $program['curriculum_type'],
                    'curriculum_type_name' => $academic->getCurriculumTypeName($program['curriculum_type']),
                    'subjects' => $academic->formatJsonArray($program['subjects']),
                    'learning_methods' => $academic->formatJsonArray($program['learning_methods']),
                    'assessment_methods' => $academic->formatJsonArray($program['assessment_methods']),
                    'image' => $program['image'] ? '../admin/' . $program['image'] : null,
                    'sort_order' => (int)$program['sort_order'],
                    'created_at' => $program['created_at']
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $formatted_programs,
                'curriculum_type' => $curriculum_type,
                'curriculum_type_name' => $academic->getCurriculumTypeName($curriculum_type),
                'total' => count($formatted_programs)
            ], JSON_PRETTY_PRINT);
            break;
            
        case 'categories':
            // Get available grade levels and curriculum types
            $grade_levels = [];
            foreach (['1', '2', '3', '4', '5', '6', 'semua'] as $grade) {
                $grade_levels[] = [
                    'value' => $grade,
                    'name' => $academic->getGradeLevelName($grade)
                ];
            }
            
            $curriculum_types = [];
            foreach (['nasional', 'internasional', 'muatan_lokal'] as $curriculum) {
                $curriculum_types[] = [
                    'value' => $curriculum,
                    'name' => $academic->getCurriculumTypeName($curriculum)
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'grade_levels' => $grade_levels,
                    'curriculum_types' => $curriculum_types
                ]
            ], JSON_PRETTY_PRINT);
            break;
            
        default:
            throw new Exception('Aksi tidak dikenal');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>
