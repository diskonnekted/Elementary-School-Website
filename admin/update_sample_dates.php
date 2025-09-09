<?php
require_once 'includes/functions.php';

echo "🔄 Updating Sample Data Dates\n";
echo "=============================\n\n";

try {
    // Initialize database
    $database = new Database();
    $db = $database->getConnection();
    
    // Update expired items with future dates
    $updates = [
        [
            'id' => 1,
            'title' => 'Pengumuman Kegiatan Pendidikan Integritas Semester Genap 2025',
            'expiry_date' => '2025-12-31'
        ],
        [
            'id' => 2,
            'title' => 'Kalender Akademik 2025/2026 - Tema Pendidikan Anti Korupsi',
            'expiry_date' => '2026-07-31'
        ],
        [
            'id' => 5,
            'title' => 'Pengumuman Kompetisi "Aku Anak Jujur" 2025',
            'expiry_date' => '2025-04-15'
        ]
    ];
    
    foreach ($updates as $update) {
        $query = "UPDATE general_info SET title = ?, expiry_date = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $result = $stmt->execute([$update['title'], $update['expiry_date'], $update['id']]);
        
        if ($result) {
            echo "✅ Updated ID " . $update['id'] . ": " . $update['title'] . "\n";
            echo "   New expiry date: " . $update['expiry_date'] . "\n\n";
        } else {
            echo "❌ Failed to update ID " . $update['id'] . "\n\n";
        }
    }
    
    // Also update content to reflect current year
    $content_updates = [
        [
            'id' => 1,
            'content' => 'SD Cerdas Ceria dengan bangga mengumumkan dimulainya program Pendidikan Integritas untuk semester genap 2025. Program ini meliputi:

1. Pelatihan nilai-nilai kejujuran untuk siswa kelas 1-6
2. Workshop anti korupsi untuk guru dan staff
3. Kampanye "Jujur itu Keren" di lingkungan sekolah
4. Kompetisi poster dan puisi bertema integritas

Kegiatan akan dimulai pada tanggal 15 Februari 2025 dan berlangsung hingga akhir semester. Mari bersama-sama membangun karakter integritas sejak dini!'
        ],
        [
            'id' => 2,
            'content' => 'Kalender akademik tahun pelajaran 2025/2026 dengan integrasi pendidikan anti korupsi:

SEMESTER GENAP:
- Februari 2025: Bulan Kejujuran
  * Minggu ke-2: Pelatihan Guru Pendidikan Integritas
  * Minggu ke-3: Launching Program "Sekolah Jujur"

- Maret 2025: Bulan Tanggung Jawab
  * Minggu ke-1: Implementasi Kantin Kejujuran
  * Minggu ke-2: Workshop Orang Tua "Mendidik Anak Berintegritas"

- April 2025: Bulan Transparansi
  * Minggu ke-1: Open House Keuangan Sekolah
  * Minggu ke-3: Festival Integritas SD Cerdas Ceria

- Mei 2025: Bulan Akuntabilitas
  * Evaluasi program pendidikan anti korupsi
  * Penilaian akhir semester'
        ],
        [
            'id' => 5,
            'content' => 'SD Cerdas Ceria mengadakan kompetisi "Aku Anak Jujur" untuk seluruh siswa dengan ketentuan:

KATEGORI LOMBA:
1. Poster Anti Korupsi (Kelas 1-3)
2. Puisi Integritas (Kelas 4-6)  
3. Drama Pendek Nilai-Nilai Kejujuran (Kelas 5-6)
4. Komik Digital Anti Korupsi (Kelas 4-6)

JADWAL:
- Pendaftaran: 1-15 Maret 2025
- Pengumpulan karya: 16-30 Maret 2025
- Penjurian: 1-7 April 2025
- Pengumuman pemenang: 10 April 2025

HADIAH:
- Juara 1: Piala + Sertifikat + Uang pembinaan Rp 500.000
- Juara 2: Piala + Sertifikat + Uang pembinaan Rp 300.000  
- Juara 3: Piala + Sertifikat + Uang pembinaan Rp 200.000

Daftar ke wali kelas masing-masing. Mari tunjukkan kreativitasmu!'
        ]
    ];
    
    foreach ($content_updates as $update) {
        $query = "UPDATE general_info SET content = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $result = $stmt->execute([$update['content'], $update['id']]);
        
        if ($result) {
            echo "✅ Updated content for ID " . $update['id'] . "\n";
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 Sample data updated successfully!\n";
    echo "✅ All items now have current/future dates\n";
    echo "✅ Content updated to reflect current year\n";
    echo "\n🌐 Test the frontend: http://localhost/sd/info.php\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
