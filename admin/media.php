<?php
$page_title = 'Galeri Media';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Ensure user is logged in
Auth::requireLogin();

if (!Auth::canEditContent()) {
    header('Location: index.php');
    exit;
}

// Define Directory Mappings
// Key = URL parameter 'dir'
// Value = [
//    'path' => File system path (relative to admin/ or absolute),
//    'url' => URL prefix for <img> tags (relative to admin/ or absolute),
//    'label' => Display Name
// ]
$directories = [
    'uploads' => [
        'path' => 'uploads/',
        'url' => 'uploads/',
        'label' => 'Uploads (Root)'
    ],
    'news' => [
        'path' => 'uploads/news/',
        'url' => 'uploads/news/',
        'label' => 'Berita'
    ],
    'academic' => [
        'path' => 'uploads/academic/',
        'url' => 'uploads/academic/',
        'label' => 'Akademik'
    ],
    'innovations' => [
        'path' => 'uploads/innovations/',
        'url' => 'uploads/innovations/',
        'label' => 'Inovasi'
    ],
    'public_images' => [
        'path' => '../images/',
        'url' => '../images/',
        'label' => 'Public Images'
    ],
    'public_favicon' => [
        'path' => '../images/favicon/',
        'url' => '../images/favicon/',
        'label' => 'Favicon'
    ]
];

// Get current directory key
$current_key = $_GET['dir'] ?? 'uploads';
if (!array_key_exists($current_key, $directories)) {
    $current_key = 'uploads';
}

$current_dir_config = $directories[$current_key];
$current_fs_path = $current_dir_config['path'];
$current_url_prefix = $current_dir_config['url'];

// Helper to get files
function getFiles($path, $url_prefix) {
    $files = [];
    
    // Create directory if it doesn't exist (only for uploads, not system dirs)
    if (!file_exists($path)) {
        if (strpos($path, 'uploads') !== false) {
            mkdir($path, 0755, true);
        } else {
            return []; // Don't create system dirs if missing
        }
    }
    
    if (is_dir($path)) {
        $items = scandir($path);
        foreach ($items as $item) {
            // Skip . and .. and any hidden files (like .htaccess)
            if ($item == '.' || $item == '..' || strpos($item, '.') === 0) {
                continue;
            }

            $full_path = $path . $item;
            if (is_file($full_path)) {
                $files[] = [
                    'name' => $item,
                    'path' => $path . $item, // File system path
                    'url' => $url_prefix . $item, // Browser URL
                    'size' => filesize($full_path),
                    'time' => filemtime($full_path),
                    'type' => pathinfo($full_path, PATHINFO_EXTENSION),
                    'is_image' => in_array(strtolower(pathinfo($full_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico'])
                ];
            }
        }
    }
    return $files;
}

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validateCSRFToken($csrf_token)) {
        setAlert('Security check failed!', 'error');
    } else {
        // Upload
        if (isset($_POST['action']) && $_POST['action'] === 'upload') {
            $target_key = $_POST['directory_key'] ?? 'uploads';
            
            if (array_key_exists($target_key, $directories)) {
                $target_config = $directories[$target_key];
                $target_path = $target_config['path'];
                
                // Ensure directory exists
                if (!file_exists($target_path)) {
                    mkdir($target_path, 0755, true);
                }
                
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['file'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'svg', 'ico', 'webp'];
                    
                    if (in_array($ext, $allowed)) {
                        // Generate clean filename
                        $filename = pathinfo($file['name'], PATHINFO_FILENAME);
                        $clean_filename = createSlug($filename) . '.' . $ext;
                        
                        // Avoid overwrite
                        $counter = 1;
                        while (file_exists($target_path . $clean_filename)) {
                            $clean_filename = createSlug($filename) . '-' . $counter . '.' . $ext;
                            $counter++;
                        }
                        
                        if (move_uploaded_file($file['tmp_name'], $target_path . $clean_filename)) {
                            setAlert('File berhasil diupload!', 'success');
                        } else {
                            setAlert('Gagal mengupload file.', 'error');
                        }
                    } else {
                        setAlert('Format file tidak diizinkan.', 'error');
                    }
                } else {
                    setAlert('Pilih file untuk diupload.', 'error');
                }
            } else {
                setAlert('Folder tujuan tidak valid.', 'error');
            }
        }
        
        // Delete
        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $target_key = $_POST['directory_key'] ?? '';
            $filename = $_POST['filename'] ?? '';
            
            if (array_key_exists($target_key, $directories) && $filename) {
                $dir_config = $directories[$target_key];
                $file_path = $dir_config['path'] . $filename;
                
                // Security check: ensure path is within intended directory
                $real_dir = realpath($dir_config['path']);
                // Use basename to prevent directory traversal in filename
                $safe_filename = basename($filename);
                $file_path = $dir_config['path'] . $safe_filename;
                $real_file = realpath($file_path);
                
                if ($real_file && $real_dir && strpos($real_file, $real_dir) === 0 && file_exists($real_file)) {
                    // Check if file is writable
                    if (is_writable($real_file)) {
                        if (unlink($real_file)) {
                            setAlert('File berhasil dihapus!', 'success');
                        } else {
                            $error = error_get_last();
                            setAlert('Gagal menghapus file. Error: ' . ($error['message'] ?? 'Unknown error'), 'error');
                        }
                    } else {
                        // Try to change permissions
                        if (chmod($real_file, 0666) && unlink($real_file)) {
                             setAlert('File berhasil dihapus (setelah chmod)!', 'success');
                        } else {
                             setAlert('File tidak dapat dihapus (permission denied).', 'error');
                        }
                    }
                } else {
                    setAlert('File tidak valid atau tidak ditemukan. Path: ' . htmlspecialchars($file_path), 'error');
                }
            } else {
                setAlert('Parameter hapus tidak valid.', 'error');
            }
        }
    }
    
    // Redirect to avoid resubmission
    header('Location: media.php?dir=' . urlencode($current_key));
    exit;
}

// Get files for current directory
$files = getFiles($current_fs_path, $current_url_prefix);

require_once 'includes/admin_header.php';
?>

<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Galeri Media</h2>
            <p class="text-gray-600">Kelola file dan gambar website</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-upload mr-2"></i>Upload File
            </button>
        </div>
    </div>

    <!-- Directory Navigation -->
    <div class="flex space-x-2 overflow-x-auto pb-2">
        <?php foreach ($directories as $key => $config): ?>
            <a href="media.php?dir=<?= $key ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap <?= $current_key === $key ? 'bg-primary-100 text-primary-700' : 'bg-white text-gray-600 hover:bg-gray-50' ?>">
                <i class="fas fa-folder mr-2"></i><?= htmlspecialchars($config['label']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- File Grid -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4 text-sm text-gray-500">
            Menampilkan file di: <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($current_fs_path) ?></span>
        </div>

        <?php if (empty($files)): ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Folder Kosong</h3>
                <p class="text-gray-500">Belum ada file di folder ini.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <?php foreach ($files as $file): ?>
                <div class="group relative bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <!-- Preview -->
                    <div class="aspect-w-1 aspect-h-1 mb-3 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center relative">
                        <?php if ($file['is_image']): ?>
                            <img src="<?= htmlspecialchars($file['url']) ?>" alt="<?= htmlspecialchars($file['name'], ENT_QUOTES) ?>" class="object-cover w-full h-32 rounded-lg">
                        <?php else: ?>
                            <div class="w-full h-32 flex items-center justify-center">
                                <i class="fas fa-file-alt text-gray-400 text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Info -->
                    <div class="text-sm">
                        <p class="font-medium text-gray-900 truncate" title="<?= htmlspecialchars($file['name'], ENT_QUOTES) ?>">
                            <?= htmlspecialchars($file['name']) ?>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            <?= formatFileSize($file['size']) ?>
                        </p>
                    </div>

                    <!-- Actions Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <div class="flex space-x-2">
                            <a href="<?= htmlspecialchars($file['url']) ?>" target="_blank" class="p-2 bg-white rounded-full text-gray-700 hover:text-primary-600 transition-colors" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if (Auth::canDeleteContent()): ?>
                            <button onclick="confirmDelete('<?= $current_key ?>', '<?= htmlspecialchars($file['name'], ENT_QUOTES) ?>')" class="p-2 bg-white rounded-full text-gray-700 hover:text-red-600 transition-colors" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('uploadModal').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <input type="hidden" name="action" value="upload">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Upload File
                    </h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Folder Tujuan</label>
                            <select name="directory_key" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <?php foreach ($directories as $key => $config): ?>
                                    <option value="<?= $key ?>" <?= $current_key === $key ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($config['label']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File</label>
                            <input type="file" name="file" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF, PDF, DOC. Max: 5MB.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Upload
                    </button>
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Hapus File</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus file ini? Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form action="" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="directory_key" id="deleteDirKey">
                    <input type="hidden" name="filename" id="deleteFilename">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(dirKey, filename) {
        document.getElementById('deleteDirKey').value = dirKey;
        document.getElementById('deleteFilename').value = filename;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>

<?php include 'includes/admin_footer.php'; ?>
