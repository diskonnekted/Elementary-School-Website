<?php
// Script untuk menambahkan sample news data
// Akses: http://localhost/sd/admin/add_sample_news.php

echo "<h1>🗞️ Menambah Sample News Data</h1>";

try {
    require_once 'config/database.php';
    require_once 'includes/functions.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Database connection failed');
    }
    
    echo "<p style='color: green;'>✅ Database connected successfully</p>";
    
    // Sample news data
    $sampleNews = [
        [
            'title' => 'Prestasi Gemilang Siswa SD Cerdas Ceria di Olimpiade Matematika Nasional',
            'content' => '<p>Siswa-siswa SD Cerdas Ceria berhasil meraih prestasi membanggakan dalam Olimpiade Matematika Nasional 2024. Tiga siswa terbaik kami berhasil meraih medali emas, perak, dan perunggu dalam kompetisi bergengsi ini.</p>

<p>Kompetisi yang diselenggarakan di Jakarta pada tanggal 15-17 September 2024 ini diikuti oleh lebih dari 500 siswa dari seluruh Indonesia. Prestasi ini membuktikan kualitas pendidikan matematika yang diterapkan di SD Cerdas Ceria.</p>

<p><strong>Para juara dari SD Cerdas Ceria:</strong></p>
<ul>
<li>Ahmad Fadhil (Kelas 6A) - Medali Emas</li>
<li>Siti Nurhaliza (Kelas 6B) - Medali Perak</li>
<li>Budi Santoso (Kelas 5A) - Medali Perunggu</li>
</ul>

<p>Kepala Sekolah, Bapak Dr. Surya Wijaya, M.Pd, menyampaikan rasa bangga atas pencapaian siswa-siswanya. "Ini adalah hasil dari kerja keras siswa, dukungan orang tua, dan dedikasi guru-guru kami dalam memberikan pendidikan terbaik," ujarnya.</p>',
            'excerpt' => 'Tiga siswa SD Cerdas Ceria meraih medali emas, perak, dan perunggu di Olimpiade Matematika Nasional 2024, membanggakan sekolah dan orang tua.',
            'category' => 'prestasi',
            'status' => 'published',
            'is_featured' => 1
        ],
        [
            'title' => 'Kegiatan Market Day: Melatih Jiwa Entrepreneur Sejak Dini',
            'content' => '<p>SD Cerdas Ceria menggelar kegiatan Market Day yang meriah pada hari Jumat, 20 September 2024. Kegiatan ini bertujuan untuk melatih jiwa entrepreneur siswa sejak dini sekaligus mengaplikasikan pembelajaran matematika dalam kehidupan sehari-hari.</p>

<p>Seluruh siswa dari kelas 1 hingga 6 berpartisipasi aktif dalam kegiatan ini. Mereka menjual berbagai macam produk mulai dari makanan ringan, minuman segar, kerajinan tangan, hingga tanaman hias.</p>

<p><strong>Highlight kegiatan Market Day:</strong></p>
<ul>
<li>30 stand penjualan yang dikelola siswa</li>
<li>Produk kreatif hasil karya siswa</li>
<li>Pembelajaran praktek jual beli</li>
<li>Penghitungan keuntungan dan kerugian</li>
</ul>

<p>Guru koordinator kegiatan, Ibu Sari Dewi, S.Pd, menjelaskan bahwa kegiatan ini sangat bermanfaat untuk mengembangkan soft skill siswa seperti komunikasi, tanggung jawab, dan jiwa leadership.</p>',
            'excerpt' => 'Kegiatan Market Day mengajarkan siswa tentang kewirausahaan dan aplikasi matematika dalam jual beli dengan 30 stand kreatif.',
            'category' => 'kegiatan',
            'status' => 'published',
            'is_featured' => 1
        ],
        [
            'title' => 'Implementasi Kurikulum Merdeka di SD Cerdas Ceria',
            'content' => '<p>SD Cerdas Ceria telah resmi mengimplementasikan Kurikulum Merdeka pada tahun ajaran 2024/2025. Perubahan ini membawa angin segar dalam dunia pendidikan di sekolah kami dengan pendekatan pembelajaran yang lebih fleksibel dan berpusat pada siswa.</p>

<p>Kurikulum Merdeka memberikan keleluasaan kepada guru untuk menciptakan pembelajaran yang berkualitas sesuai dengan kebutuhan dan lingkungan belajar siswa. Hal ini sejalan dengan visi SD Cerdas Ceria untuk menciptakan generasi yang cerdas, kreatif, dan berkarakter.</p>

<p><strong>Fitur unggulan Kurikulum Merdeka di SD Cerdas Ceria:</strong></p>
<ul>
<li>Pembelajaran berbasis proyek (Project Based Learning)</li>
<li>Asesmen yang lebih holistik</li>
<li>Pengembangan profil pelajar Pancasila</li>
<li>Integrasi teknologi dalam pembelajaran</li>
</ul>

<p>Para guru telah mengikuti pelatihan intensif untuk memastikan implementasi kurikulum berjalan dengan baik dan memberikan dampak positif bagi perkembangan siswa.</p>',
            'excerpt' => 'SD Cerdas Ceria mengimplementasikan Kurikulum Merdeka dengan pendekatan pembelajaran yang fleksibel dan berpusat pada siswa.',
            'category' => 'umum',
            'status' => 'published',
            'is_featured' => 0
        ],
        [
            'title' => 'Pengumuman Libur Semester dan Persiapan Tahun Ajaran Baru',
            'content' => '<p>Dalam rangka libur semester ganjil tahun ajaran 2024/2025, SD Cerdas Ceria mengumumkan jadwal libur dan persiapan tahun ajaran baru sebagai berikut:</p>

<p><strong>Jadwal Libur Semester:</strong></p>
<ul>
<li>Libur Semester: 18 Desember 2024 - 6 Januari 2025</li>
<li>Masuk sekolah: Senin, 7 Januari 2025</li>
<li>Pembagian rapor: 16 Desember 2024</li>
</ul>

<p><strong>Persiapan Semester Genap:</strong></p>
<ul>
<li>Pendaftaran siswa baru: 2-15 Januari 2025</li>
<li>Rapat orang tua: 5 Januari 2025</li>
<li>Hari orientasi: 7-8 Januari 2025</li>
</ul>

<p>Selama masa libur, siswa diharapkan tetap menjaga kesehatan dan melakukan kegiatan positif. Sekolah juga menyediakan program pengayaan untuk siswa yang ingin mengikuti kegiatan tambahan.</p>

<p>Untuk informasi lebih lanjut, silakan hubungi kantor sekolah di nomor (021) 12345678 atau email info@sdcerdasceria.sch.id</p>',
            'excerpt' => 'Pengumuman jadwal libur semester dan persiapan tahun ajaran baru 2025 untuk siswa dan orang tua.',
            'category' => 'pengumuman',
            'status' => 'published',
            'is_featured' => 0
        ],
        [
            'title' => 'Pelatihan Digital Literacy untuk Guru SD Cerdas Ceria',
            'content' => '<p>Dalam upaya meningkatkan kualitas pembelajaran di era digital, SD Cerdas Ceria mengadakan pelatihan Digital Literacy untuk seluruh guru pada tanggal 25-27 September 2024. Pelatihan ini bertujuan untuk meningkatkan kompetensi guru dalam mengintegrasikan teknologi dalam proses pembelajaran.</p>

<p>Pelatihan ini menghadirkan narasumber ahli dari berbagai bidang teknologi pendidikan dan diikuti oleh 35 guru dari berbagai tingkatan kelas. Materi pelatihan mencakup penggunaan platform pembelajaran digital, pembuatan konten interaktif, dan strategi pembelajaran hybrid.</p>

<p><strong>Materi pelatihan yang diberikan:</strong></p>
<ul>
<li>Pengenalan platform Google Workspace for Education</li>
<li>Pembuatan media pembelajaran interaktif</li>
<li>Strategi pembelajaran online dan offline</li>
<li>Assessment digital dan feedback system</li>
</ul>

<p>Hasil dari pelatihan ini diharapkan dapat meningkatkan kualitas pembelajaran dan mempersiapkan siswa menghadapi tantangan pendidikan di masa depan.</p>',
            'excerpt' => 'Pelatihan Digital Literacy untuk 35 guru SD Cerdas Ceria guna meningkatkan kompetensi teknologi pembelajaran.',
            'category' => 'kegiatan',
            'status' => 'published',
            'is_featured' => 0
        ]
    ];
    
    // Clear existing sample data (optional)
    echo "<p>Menghapus data sample lama...</p>";
    $db->exec("DELETE FROM news WHERE title LIKE '%Sample%' OR title LIKE '%Test%'");
    
    // Insert sample news
    $query = "INSERT INTO news (title, slug, content, excerpt, category, status, author_id, is_featured, published_at, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    
    $stmt = $db->prepare($query);
    $inserted = 0;
    
    foreach ($sampleNews as $news) {
        $slug = createSlug($news['title']);
        
        // Check if slug already exists
        $checkQuery = "SELECT COUNT(*) FROM news WHERE slug = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$slug]);
        
        if ($checkStmt->fetchColumn() > 0) {
            $slug = $slug . '-' . time();
        }
        
        $result = $stmt->execute([
            $news['title'],
            $slug,
            $news['content'],
            $news['excerpt'],
            $news['category'],
            $news['status'],
            1, // author_id (admin)
            $news['is_featured'],
        ]);
        
        if ($result) {
            $inserted++;
            echo "<p style='color: green;'>✅ Berhasil menambah: " . htmlspecialchars($news['title']) . "</p>";
        }
    }
    
    echo "<h2 style='color: green;'>🎉 Selesai!</h2>";
    echo "<p><strong>Total berita berhasil ditambahkan:</strong> $inserted</p>";
    echo "<p><a href='news.php' style='background: #6366f1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Lihat di Admin</a></p>";
    echo "<p><a href='../berita.html' style='background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;'>Lihat di Frontend</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f5f5f5;
}
h1, h2 {
    color: #333;
}
</style>
