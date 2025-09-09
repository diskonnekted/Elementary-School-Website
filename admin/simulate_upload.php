<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/functions.php';

echo "<h2>Simulate File Upload Test</h2>";

// Path to our test image
$test_image_path = 'test_image.png';

if (!file_exists($test_image_path)) {
    echo "Test image not found. Please run create_test_image.php first.<br>";
    exit;
}

echo "<h3>Test Image Info:</h3>";
echo "Path: " . $test_image_path . "<br>";
echo "Size: " . filesize($test_image_path) . " bytes<br>";
echo "Type: " . mime_content_type($test_image_path) . "<br>";

// Simulate $_FILES array
$simulated_file = array(
    'name' => 'test_image.png',
    'type' => 'image/png',
    'size' => filesize($test_image_path),
    'tmp_name' => $test_image_path, // Using actual file path as temp
    'error' => UPLOAD_ERR_OK
);

echo "<h3>Simulated \$_FILES array:</h3>";
echo "<pre>";
print_r($simulated_file);
echo "</pre>";

// Test directory info
echo "<h3>Directory Info:</h3>";
echo "Current working directory: " . getcwd() . "<br>";
echo "uploads/ exists: " . (file_exists('uploads/') ? 'Yes' : 'No') . "<br>";
echo "uploads/ writable: " . (is_writable('uploads/') ? 'Yes' : 'No') . "<br>";

// Test uploadFile function step by step
echo "<h3>Step-by-step Upload Test:</h3>";

// Step 1: Check if file array is valid
echo "1. File array check: ";
if (isset($simulated_file) && is_array($simulated_file)) {
    echo "✅ Valid<br>";
} else {
    echo "❌ Invalid<br>";
    exit;
}

// Step 2: Check upload error
echo "2. Upload error check: ";
if ($simulated_file['error'] === UPLOAD_ERR_OK) {
    echo "✅ No error<br>";
} else {
    echo "❌ Error code: " . $simulated_file['error'] . "<br>";
    exit;
}

// Step 3: Check file extension
echo "3. File extension check: ";
$file_extension = strtolower(pathinfo($simulated_file['name'], PATHINFO_EXTENSION));
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
if (in_array($file_extension, $allowed_types)) {
    echo "✅ Extension '{$file_extension}' is allowed<br>";
} else {
    echo "❌ Extension '{$file_extension}' is not allowed<br>";
    exit;
}

// Step 4: Generate filename
echo "4. Generate filename: ";
$new_filename = 'news_' . uniqid() . '_' . time() . '.' . $file_extension;
echo "✅ Generated: {$new_filename}<br>";

// Step 5: Create target path
echo "5. Target path: ";
$target_dir = 'uploads/';
$target_path = $target_dir . $new_filename;
echo "✅ Path: {$target_path}<br>";

// Step 6: Check directory
echo "6. Directory check: ";
if (!file_exists($target_dir)) {
    if (mkdir($target_dir, 0755, true)) {
        echo "✅ Directory created<br>";
    } else {
        echo "❌ Failed to create directory<br>";
        exit;
    }
} else {
    echo "✅ Directory exists<br>";
}

// Step 7: Check writable
echo "7. Writable check: ";
if (is_writable($target_dir)) {
    echo "✅ Directory is writable<br>";
} else {
    echo "❌ Directory is not writable<br>";
    exit;
}

// Step 8: Copy file (simulating move_uploaded_file)
echo "8. File copy test: ";
if (copy($simulated_file['tmp_name'], $target_path)) {
    echo "✅ File copied successfully<br>";
    echo "Target file exists: " . (file_exists($target_path) ? 'Yes' : 'No') . "<br>";
    echo "Target file size: " . filesize($target_path) . " bytes<br>";
    
    // Clean up test file
    unlink($target_path);
    echo "Test file cleaned up<br>";
} else {
    echo "❌ File copy failed<br>";
}

echo "<h3>Now testing actual uploadFile() function:</h3>";

// Copy test image to temp location to simulate uploaded file
$temp_file = sys_get_temp_dir() . '/test_upload_' . time() . '.png';
copy($test_image_path, $temp_file);

$real_simulated_file = array(
    'name' => 'test_image.png',
    'type' => 'image/png',
    'size' => filesize($temp_file),
    'tmp_name' => $temp_file,
    'error' => UPLOAD_ERR_OK
);

echo "Testing with temp file: " . $temp_file . "<br>";
echo "Temp file exists: " . (file_exists($temp_file) ? 'Yes' : 'No') . "<br>";

$result = uploadFile($real_simulated_file, 'uploads/', ['jpg', 'jpeg', 'png', 'gif']);

if ($result) {
    echo "✅ uploadFile() successful!<br>";
    echo "Returned filename: " . $result . "<br>";
    echo "Full path: uploads/" . $result . "<br>";
    echo "File exists: " . (file_exists('uploads/' . $result) ? 'Yes' : 'No') . "<br>";
    
    if (file_exists('uploads/' . $result)) {
        echo "File size: " . filesize('uploads/' . $result) . " bytes<br>";
    }
} else {
    echo "❌ uploadFile() failed<br>";
    
    // Check if temp file still exists
    echo "Temp file still exists: " . (file_exists($temp_file) ? 'Yes' : 'No') . "<br>";
}

// Clean up temp file if it still exists
if (file_exists($temp_file)) {
    unlink($temp_file);
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
h2, h3 { color: #333; border-bottom: 1px solid #eee; padding-bottom: 5px; }
pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style>
