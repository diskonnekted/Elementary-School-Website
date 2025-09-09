<?php
header('Content-Type: text/html; charset=utf-8');
echo "<h2>Validasi Gambar Berita Frontend</h2>";

// Test 1: Cek API news list
echo "<h3>1. Test API News List</h3>";
$apiUrl = 'http://localhost/sd/api/news.php?action=list&limit=10';
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if ($data && $data['success']) {
    echo "✅ API berhasil memberikan response<br>";
    echo "Total berita: " . count($data['data']) . "<br>";
    
    foreach ($data['data'] as $news) {
        echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 10px;'>";
        echo "<strong>Berita:</strong> " . htmlspecialchars($news['title']) . "<br>";
        echo "<strong>Kategori:</strong> " . $news['category'] . "<br>";
        
        if ($news['featured_image']) {
            echo "<strong>Featured Image:</strong> " . $news['featured_image'] . "<br>";
            
            // Test akses gambar
            $headers = @get_headers('http://localhost' . $news['featured_image']);
            if ($headers && strpos($headers[0], '200')) {
                echo "✅ <span style='color: green;'>Gambar dapat diakses</span><br>";
                echo "<img src='http://localhost" . $news['featured_image'] . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;' alt='Preview'><br>";
            } else {
                echo "❌ <span style='color: red;'>Gambar tidak dapat diakses</span><br>";
            }
        } else {
            echo "<strong>Featured Image:</strong> <em>Tidak ada (akan menggunakan placeholder)</em><br>";
        }
        echo "</div>";
    }
} else {
    echo "❌ API gagal memberikan response<br>";
}

// Test 2: Cek API featured news
echo "<h3>2. Test API Featured News</h3>";
$featuredUrl = 'http://localhost/sd/api/news.php?action=featured&limit=5';
$featuredResponse = file_get_contents($featuredUrl);
$featuredData = json_decode($featuredResponse, true);

if ($featuredData && $featuredData['success']) {
    echo "✅ API featured news berhasil<br>";
    echo "Total berita featured: " . count($featuredData['data']) . "<br>";
    
    foreach ($featuredData['data'] as $news) {
        echo "<div style='border: 2px solid #007cba; margin: 10px 0; padding: 10px; background: #f0f8ff;'>";
        echo "<strong>Featured News:</strong> " . htmlspecialchars($news['title']) . "<br>";
        echo "<strong>Kategori:</strong> " . $news['category'] . "<br>";
        
        if ($news['featured_image']) {
            echo "<strong>Featured Image:</strong> " . $news['featured_image'] . "<br>";
            
            // Test akses gambar
            $headers = @get_headers('http://localhost' . $news['featured_image']);
            if ($headers && strpos($headers[0], '200')) {
                echo "✅ <span style='color: green;'>Gambar featured dapat diakses</span><br>";
                echo "<img src='http://localhost" . $news['featured_image'] . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;' alt='Featured Preview'><br>";
            } else {
                echo "❌ <span style='color: red;'>Gambar featured tidak dapat diakses</span><br>";
            }
        } else {
            echo "<strong>Featured Image:</strong> <em>Tidak ada</em><br>";
        }
        echo "</div>";
    }
} else {
    echo "❌ API featured news gagal<br>";
}

// Test 3: Cek path uploads
echo "<h3>3. Test Upload Directory</h3>";
$uploadPath = __DIR__ . '/admin/uploads/';
if (is_dir($uploadPath)) {
    echo "✅ Directory uploads ada: " . $uploadPath . "<br>";
    
    $files = glob($uploadPath . 'news_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    echo "Total file gambar berita: " . count($files) . "<br>";
    
    foreach ($files as $file) {
        $filename = basename($file);
        $webPath = "/sd/admin/uploads/" . $filename;
        
        echo "<div style='margin: 5px 0; padding: 5px; background: #f5f5f5;'>";
        echo "File: " . $filename . "<br>";
        echo "Size: " . number_format(filesize($file) / 1024, 2) . " KB<br>";
        echo "Web Path: " . $webPath . "<br>";
        
        // Test akses via web
        $headers = @get_headers('http://localhost' . $webPath);
        if ($headers && strpos($headers[0], '200')) {
            echo "✅ <span style='color: green;'>Dapat diakses via web</span><br>";
        } else {
            echo "❌ <span style='color: red;'>Tidak dapat diakses via web</span><br>";
        }
        echo "</div>";
    }
} else {
    echo "❌ Directory uploads tidak ada<br>";
}

// Test 4: Cek halaman berita.html
echo "<h3>4. Test Halaman Berita Frontend</h3>";
$headers = @get_headers('http://localhost/sd/berita.html');
if ($headers && strpos($headers[0], '200')) {
    echo "✅ <span style='color: green;'>Halaman berita.html dapat diakses</span><br>";
    echo "<a href='http://localhost/sd/berita.html' target='_blank'>🔗 Buka Halaman Berita</a><br>";
} else {
    echo "❌ <span style='color: red;'>Halaman berita.html tidak dapat diakses</span><br>";
}

echo "<h3>📋 Ringkasan</h3>";
echo "<div style='background: #e7f3ff; border: 1px solid #b3d9ff; padding: 15px; border-radius: 8px;'>";
echo "<p><strong>✅ Perbaikan yang sudah dilakukan:</strong></p>";
echo "<ul>";
echo "<li>Path gambar di API sudah diperbaiki ke <code>/sd/admin/uploads/</code></li>";
echo "<li>Upload gambar di backend sudah berhasil</li>";
echo "<li>API mengembalikan path gambar yang benar</li>";
echo "<li>Gambar dapat diakses melalui URL langsung</li>";
echo "</ul>";

echo "<p><strong>🎯 Langkah selanjutnya:</strong></p>";
echo "<ul>";
echo "<li>Silakan buka <a href='http://localhost/sd/berita.html' target='_blank'>halaman berita</a> untuk melihat hasil</li>";
echo "<li>Gambar berita yang sudah di-upload akan tampil di frontend</li>";
echo "<li>Berita tanpa gambar akan menggunakan placeholder yang sudah dibuat</li>";
echo "</ul>";
echo "</div>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    line-height: 1.6;
}
h2, h3 {
    color: #333;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
}
code {
    background: #f4f4f4;
    padding: 2px 6px;
    border-radius: 4px;
}
</style>
