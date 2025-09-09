<?php
require_once 'includes/functions.php';
require_once 'models/Innovation.php';

echo "🚀 Menambahkan Data Sample Inovasi Pembelajaran SD Cerdas Ceria\n";
echo "=" . str_repeat("=", 65) . "\n\n";

try {
    // Initialize database
    $database = new Database();
    $db = $database->getConnection();
    $innovation = new Innovation($db);
    
    // Sample innovations with anti-corruption education integration
    $sample_innovations = [
        [
            'title' => 'Sistem Pembelajaran Digital Transparansi',
            'description' => 'Platform pembelajaran digital yang mengintegrasikan nilai-nilai transparansi dan akuntabilitas dalam setiap materi pelajaran. Siswa belajar konsep kejujuran melalui simulasi dan studi kasus interaktif yang dikembangkan khusus untuk tingkat sekolah dasar.',
            'category' => 'teknologi',
            'implementation_year' => 2024,
            'benefits' => [
                'Pembelajaran interaktif tentang nilai-nilai integritas',
                'Pemahaman konsep transparansi melalui teknologi',
                'Peningkatan literasi digital yang beretika',
                'Dokumentasi proses belajar yang akuntabel',
                'Akses materi anti-korupsi untuk semua siswa'
            ],
            'features' => [
                'Modul pembelajaran anti-korupsi terintegrasi',
                'Dashboard transparansi nilai dan prestasi',
                'Sistem pelaporan aktivitas yang terbuka',
                'Interface ramah anak dengan gamifikasi',
                'Monitoring progres belajar real-time'
            ],
            'video_url' => 'https://youtube.com/watch?v=example1',
            'is_featured' => 1,
            'is_active' => 1
        ],
        [
            'title' => 'Metode Pembelajaran Kantin Kejujuran',
            'description' => 'Metode pembelajaran praktis yang mengajarkan nilai-nilai kejujuran melalui operasional kantin sekolah. Siswa belajar mengelola transaksi dengan sistem kepercayaan tanpa penjaga, melatih kejujuran dan tanggung jawab dalam kehidupan sehari-hari.',
            'category' => 'metode',
            'implementation_year' => 2023,
            'benefits' => [
                'Pelatihan karakter kejujuran secara langsung',
                'Pembelajaran manajemen keuangan sederhana',
                'Peningkatan rasa tanggung jawab pribadi',
                'Praktik nilai-nilai integritas dalam keseharian',
                'Pembentukan kebiasaan jujur sejak dini'
            ],
            'features' => [
                'Sistem pembayaran mandiri tanpa kasir',
                'Pencatatan transaksi yang transparan',
                'Evaluasi tingkat kejujuran mingguan',
                'Reward system untuk perilaku jujur',
                'Laporan keuangan terbuka untuk siswa'
            ],
            'video_url' => null,
            'is_featured' => 1,
            'is_active' => 1
        ],
        [
            'title' => 'Kurikulum Pendidikan Karakter Anti-Korupsi',
            'description' => 'Kurikulum khusus yang mengintegrasikan pendidikan anti-korupsi ke dalam seluruh mata pelajaran. Dikembangkan dengan pendekatan yang sesuai untuk anak usia sekolah dasar, menggunakan metode storytelling, permainan edukatif, dan aktivitas praktis.',
            'category' => 'kurikulum',
            'implementation_year' => 2023,
            'benefits' => [
                'Pemahaman konsep integritas sejak dini',
                'Integrasi nilai anti-korupsi di semua mapel',
                'Pembentukan karakter yang sistematis',
                'Peningkatan kesadaran etika dan moral',
                'Persiapan generasi pemimpin yang berintegritas'
            ],
            'features' => [
                'Modul pembelajaran sesuai tingkat usia',
                'Cerita dan dongeng bertemakan kejujuran',
                'Aktivitas praktik langsung nilai integritas',
                'Assessment karakter berbasis observasi',
                'Panduan implementasi untuk guru'
            ],
            'video_url' => 'https://youtube.com/watch?v=example2',
            'is_featured' => 1,
            'is_active' => 1
        ],
        [
            'title' => 'Smart Building dengan Sistem Transparansi',
            'description' => 'Infrastruktur bangunan sekolah yang dilengkapi dengan teknologi smart untuk mendukung transparansi dan akuntabilitas. Setiap ruang dilengkapi dengan sistem monitoring yang dapat diakses publik untuk memastikan penggunaan fasilitas yang optimal dan transparan.',
            'category' => 'fasilitas',
            'implementation_year' => 2024,
            'benefits' => [
                'Transparansi penggunaan fasilitas sekolah',
                'Monitoring keamanan dan kenyamanan real-time',
                'Efisiensi energi dan biaya operasional',
                'Akuntabilitas pemeliharaan infrastruktur',
                'Lingkungan belajar yang aman dan terpantau'
            ],
            'features' => [
                'Sensor IoT di setiap ruangan',
                'Dashboard monitoring terbuka untuk publik',
                'Sistem keamanan terintegrasi',
                'Pengelolaan energi yang efisien',
                'Maintenance schedule yang transparan'
            ],
            'video_url' => null,
            'is_featured' => 0,
            'is_active' => 1
        ],
        [
            'title' => 'Aplikasi Mobile Anti-Bullying & Pelaporan',
            'description' => 'Aplikasi mobile yang memungkinkan siswa untuk melaporkan tindakan tidak etis, bullying, atau pelanggaran integritas secara anonim. Sistem ini mendukung terciptanya lingkungan sekolah yang aman dan transparan.',
            'category' => 'teknologi',
            'implementation_year' => 2024,
            'benefits' => [
                'Sistem pelaporan yang aman dan anonim',
                'Pencegahan bullying dan tindak tidak etis',
                'Peningkatan keberanian untuk berkata jujur',
                'Response time yang cepat untuk penanganan',
                'Data analytics untuk pencegahan proaktif'
            ],
            'features' => [
                'Pelaporan anonim dengan enkripsi',
                'Kategori pelaporan yang komprehensif',
                'Notifikasi real-time untuk admin',
                'Tracking status penanganan laporan',
                'Dashboard statistik untuk evaluasi'
            ],
            'video_url' => 'https://youtube.com/watch?v=example3',
            'is_featured' => 0,
            'is_active' => 1
        ],
        [
            'title' => 'Workshop Storytelling Nilai-Nilai Pancasila',
            'description' => 'Metode pembelajaran inovatif melalui workshop storytelling yang mengajarkan nilai-nilai Pancasila dengan fokus pada anti-korupsi dan integritas. Siswa belajar menceritakan dan mendengarkan kisah-kisah inspiratif tentang kejujuran dan keadilan.',
            'category' => 'metode',
            'implementation_year' => 2023,
            'benefits' => [
                'Peningkatan kemampuan komunikasi',
                'Pemahaman nilai-nilai Pancasila yang mendalam',
                'Inspirasi dari tokoh-tokoh berintegritas',
                'Kreativitas dan imajinasi yang berkembang',
                'Penanaman karakter melalui cerita'
            ],
            'features' => [
                'Koleksi cerita tokoh nasional berintegritas',
                'Workshop mingguan dengan tema berbeda',
                'Kompetisi storytelling antar kelas',
                'Dokumentasi video cerita siswa',
                'Penilaian karakter melalui cerita'
            ],
            'video_url' => null,
            'is_featured' => 0,
            'is_active' => 1
        ],
        [
            'title' => 'Laboratorium Simulasi Demokrasi Mini',
            'description' => 'Fasilitas khusus berupa laboratorium yang mensimulasikan proses demokrasi dalam skala kecil. Siswa belajar tentang pemilihan yang jujur, transparansi, dan akuntabilitas melalui simulasi pemilihan ketua kelas, OSIS, dan kegiatan demokratis lainnya.',
            'category' => 'fasilitas',
            'implementation_year' => 2023,
            'benefits' => [
                'Pemahaman proses demokrasi yang benar',
                'Pembelajaran nilai-nilai kejujuran dalam pemilu',
                'Pelatihan kepemimpinan yang berintegritas',
                'Praktik transparansi dalam pengambilan keputusan',
                'Persiapan menjadi warga negara yang baik'
            ],
            'features' => [
                'Bilik suara mini untuk simulasi',
                'Sistem penghitungan suara transparan',
                'Media campaign area untuk kampanye',
                'Recording system untuk dokumentasi',
                'Debat stage untuk diskusi terbuka'
            ],
            'video_url' => 'https://youtube.com/watch?v=example4',
            'is_featured' => 0,
            'is_active' => 1
        ],
        [
            'title' => 'Program Kurikulum Kewirausahaan Sosial',
            'description' => 'Program kurikulum yang mengajarkan kewirausahaan dengan fokus pada dampak sosial dan nilai-nilai integritas. Siswa belajar membuat bisnis kecil yang tidak hanya menguntungkan tetapi juga memberikan manfaat untuk masyarakat dengan cara yang etis.',
            'category' => 'kurikulum',
            'implementation_year' => 2024,
            'benefits' => [
                'Pembelajaran bisnis yang beretika',
                'Penanaman jiwa entrepreneurship yang bertanggung jawab',
                'Pemahaman impact investing sejak dini',
                'Kreativitas dalam menyelesaikan masalah sosial',
                'Karakter kepemimpinan yang berintegritas'
            ],
            'features' => [
                'Modul bisnis plan untuk anak',
                'Praktik usaha kecil di lingkungan sekolah',
                'Mentoring dari pengusaha sukses',
                'Kompetisi ide bisnis sosial',
                'Evaluasi dampak sosial dari usaha siswa'
            ],
            'video_url' => null,
            'is_featured' => 0,
            'is_active' => 1
        ]
    ];
    
    echo "Menambahkan " . count($sample_innovations) . " data sample inovasi...\n\n";
    
    $success_count = 0;
    foreach ($sample_innovations as $index => $data) {
        try {
            $innovation->title = $data['title'];
            $innovation->description = $data['description'];
            $innovation->category = $data['category'];
            $innovation->implementation_year = $data['implementation_year'];
            $innovation->benefits = json_encode($data['benefits']);
            $innovation->features = json_encode($data['features']);
            $innovation->image = null; // Will be added manually via admin
            $innovation->video_url = $data['video_url'];
            $innovation->is_featured = $data['is_featured'];
            $innovation->is_active = $data['is_active'];
            
            if ($innovation->create()) {
                $success_count++;
                echo "✅ " . ($index + 1) . ". " . $data['title'] . "\n";
                echo "   Kategori: " . $innovation->getCategoryName($data['category']) . "\n";
                echo "   Tahun: " . $data['implementation_year'] . "\n";
                echo "   Status: " . ($data['is_featured'] ? 'Unggulan' : 'Biasa') . "\n\n";
            } else {
                echo "❌ Gagal menambah: " . $data['title'] . "\n\n";
            }
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n\n";
        }
    }
    
    echo str_repeat("=", 65) . "\n";
    echo "📊 RINGKASAN:\n";
    echo "✅ Berhasil ditambahkan: $success_count dari " . count($sample_innovations) . " inovasi\n";
    echo "🎯 Data sample inovasi dengan tema pendidikan anti korupsi telah siap!\n";
    echo "\n";
    echo "🌐 Akses halaman:\n";
    echo "   - Frontend: http://localhost/sd/inovasi.php\n";
    echo "   - Admin: http://localhost/sd/admin/innovation.php\n";
    echo "👤 Login dengan akun admin yang sudah ada untuk mengakses backend\n\n";
    
    // Show statistics
    $stats = $innovation->getStats();
    
    echo "📈 STATISTIK DATA:\n";
    echo "Total Inovasi: {$stats['total']}\n";
    echo "Unggulan: {$stats['featured']}\n";
    echo "\nBerdasarkan Kategori:\n";
    foreach ($stats['by_category'] as $cat => $count) {
        echo "  - " . $innovation->getCategoryName($cat) . ": $count\n";
    }
    echo "\nBerdasarkan Tahun Implementasi:\n";
    foreach ($stats['by_year'] as $year => $count) {
        echo "  - Tahun $year: $count inovasi\n";
    }
    
    echo "\n🎨 TEMA ANTI-KORUPSI TERINTEGRASI:\n";
    echo "✅ Transparansi dalam teknologi pembelajaran\n";
    echo "✅ Kejujuran melalui metode kantin kepercayaan\n";
    echo "✅ Integritas dalam kurikulum karakter\n";
    echo "✅ Akuntabilitas dalam pengelolaan fasilitas\n";
    echo "✅ Pelaporan etis melalui teknologi\n";
    echo "✅ Nilai Pancasila dan demokrasi yang berintegritas\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🎉 Selesai! Sistem inovasi pembelajaran siap digunakan.\n";
echo "📝 Catatan: Gambar dapat ditambahkan melalui halaman admin\n";
?>
