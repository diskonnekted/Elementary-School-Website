<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'admin/config/database.php';
require_once 'admin/models/Transparency.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $transparency = new Transparency($db);
    
    $action = $_GET['action'] ?? 'list';
    
    switch ($action) {
        case 'list':
            // Get parameters
            $section_type = $_GET['section_type'] ?? '';
            $search = $_GET['search'] ?? '';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 0;
            
            // Get active transparency data
            $transparencies = $transparency->getAll($section_type);
            
            // Apply search filter if provided
            if (!empty($search)) {
                $transparencies = array_filter($transparencies, function($item) use ($search) {
                    return stripos($item['title'], $search) !== false || 
                           stripos($item['content'], $search) !== false;
                });
                $transparencies = array_values($transparencies); // Reindex array
            }
            
            // Apply limit if provided
            if ($limit > 0) {
                $transparencies = array_slice($transparencies, 0, $limit);
            }
            
            // Format data for frontend
            $formatted_data = [];
            foreach ($transparencies as $item) {
                $formatted_data[] = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'content_preview' => strlen($item['content']) > 200 ? 
                                        substr(strip_tags($item['content']), 0, 200) . '...' : 
                                        strip_tags($item['content']),
                    'section_type' => $item['section_type'],
                    'section_name' => getSectionTypeName($item['section_type']),
                    'file_attachment' => $item['file_attachment'],
                    'file_url' => $item['file_attachment'] ? 'admin/uploads/attachments/' . $item['file_attachment'] : null,
                    'sort_order' => (int)$item['sort_order'],
                    'created_at' => $item['created_at'],
                    'formatted_date' => date('d F Y', strtotime($item['created_at'])),
                    'has_file' => !empty($item['file_attachment'])
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $formatted_data,
                'total' => count($formatted_data),
                'filters' => [
                    'section_type' => $section_type,
                    'search' => $search,
                    'limit' => $limit
                ]
            ]);
            break;
            
        case 'view':
            // Get single transparency item by ID
            $id = $_GET['id'] ?? 0;
            
            if (!$id) {
                throw new Exception('ID tidak ditemukan');
            }
            
            $item = $transparency->getById($id);
            
            if (!$item || !$item['is_active']) {
                throw new Exception('Data tidak ditemukan atau tidak aktif');
            }
            
            // Format single item
            $formatted_item = [
                'id' => $item['id'],
                'title' => $item['title'],
                'content' => $item['content'],
                'section_type' => $item['section_type'],
                'section_name' => getSectionTypeName($item['section_type']),
                'file_attachment' => $item['file_attachment'],
                'file_url' => $item['file_attachment'] ? 'admin/uploads/attachments/' . $item['file_attachment'] : null,
                'sort_order' => (int)$item['sort_order'],
                'created_at' => $item['created_at'],
                'formatted_date' => date('d F Y', strtotime($item['created_at'])),
                'has_file' => !empty($item['file_attachment'])
            ];
            
            echo json_encode([
                'success' => true,
                'data' => $formatted_item
            ]);
            break;
            
        case 'sections':
            // Get available section types with data count
            $sections = getSectionTypes();
            $section_stats = [];
            
            foreach ($sections as $type => $name) {
                $count = count($transparency->getAll($type));
                if ($count > 0) {
                    $section_stats[] = [
                        'type' => $type,
                        'name' => $name,
                        'count' => $count,
                        'icon' => getSectionIcon($type)
                    ];
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => $section_stats,
                'total_sections' => count($section_stats)
            ]);
            break;
            
        case 'stats':
            // Get statistics for transparency page
            $stats = $transparency->getStats();
            $section_breakdown = [];
            
            if (isset($stats['by_section'])) {
                foreach ($stats['by_section'] as $section => $count) {
                    $section_breakdown[] = [
                        'type' => $section,
                        'name' => getSectionTypeName($section),
                        'count' => (int)$count,
                        'icon' => getSectionIcon($section)
                    ];
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'total_items' => (int)($stats['total'] ?? 0),
                    'active_items' => (int)($stats['active'] ?? 0),
                    'sections' => $section_breakdown,
                    'latest_update' => date('Y-m-d H:i:s')
                ]
            ]);
            break;
            
        case 'latest':
            // Get latest transparency items
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $latest_items = $transparency->getLatest($limit);
            
            $formatted_data = [];
            foreach ($latest_items as $item) {
                $formatted_data[] = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'content_preview' => strlen($item['content']) > 150 ? 
                                        substr(strip_tags($item['content']), 0, 150) . '...' : 
                                        strip_tags($item['content']),
                    'section_type' => $item['section_type'],
                    'section_name' => getSectionTypeName($item['section_type']),
                    'has_file' => !empty($item['file_attachment']),
                    'created_at' => $item['created_at'],
                    'formatted_date' => date('d M Y', strtotime($item['created_at']))
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $formatted_data,
                'total' => count($formatted_data)
            ]);
            break;
            
        default:
            throw new Exception('Action tidak valid');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => true
    ]);
}

// Helper functions
function getSectionTypes() {
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

function getSectionTypeName($type) {
    $types = getSectionTypes();
    return $types[$type] ?? $type;
}

function getSectionIcon($type) {
    $icons = [
        'financial' => '💰',
        'budget' => '📊',
        'governance' => '🏛️',
        'reports' => '📋',
        'policies' => '📜',
        'procurement' => '🛒',
        'other' => '📄'
    ];
    return $icons[$type] ?? '📄';
}
?>
