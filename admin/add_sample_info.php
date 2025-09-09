<?php
require_once 'includes/functions.php';
require_once 'models/GeneralInfo.php';

echo "🌟 Menambahkan Data Sample Informasi Umum SD Cerdas Ceria\n";
echo "=" . str_repeat("=", 60) . "\n\n";

try {
    // Initialize database
    $database = new Database();
    $db = $database->getConnection();
    $generalInfo = new GeneralInfo($db);
    
    // Sample data with anti-corruption education theme
    $sample_infos = [
        [
            'title' => 'Pengumuman Kegiatan Pendidikan Integritas Semester Genap 2024',
            'content' => 'SD Cerdas Ceria dengan bangga mengumumkan dimulainya program Pendidikan Integritas untuk semester genap 2024. Program ini meliputi:

1. Pelatihan nilai-nilai kejujuran untuk siswa kelas 1-6
2. Workshop anti korupsi untuk guru dan staff
3. Kampanye "Jujur itu Keren" di lingkungan sekolah
4. Kompetisi poster dan puisi bertema integritas

Kegiatan akan dimulai pada tanggal 15 Februari 2024 dan berlangsung hingga akhir semester. Mari bersama-sama membangun karakter integritas sejak dini!',
            'type' => 'pengumuman',
            'priority' => 'tinggi',
            'expiry_date' => '2024-12-31',
            'attachment' => null,
            'is_active' => 1
        ],
        [
            'title' => 'Kalender Akademik 2024/2025 - Tema Pendidikan Anti Korupsi',
            'content' => 'Kalender akademik tahun pelajaran 2024/2025 dengan integrasi pendidikan anti korupsi:

SEMESTER GENAP:
- Februari 2024: Bulan Kejujuran
  * Minggu ke-2: Pelatihan Guru Pendidikan Integritas
  * Minggu ke-3: Launching Program "Sekolah Jujur"

- Maret 2024: Bulan Tanggung Jawab
  * Minggu ke-1: Implementasi Kantin Kejujuran
  * Minggu ke-2: Workshop Orang Tua "Mendidik Anak Berintegritas"

- April 2024: Bulan Transparansi
  * Minggu ke-1: Open House Keuangan Sekolah
  * Minggu ke-3: Festival Integritas SD Cerdas Ceria

- Mei 2024: Bulan Akuntabilitas
  * Evaluasi program pendidikan anti korupsi
  * Penilaian akhir semester',
            'type' => 'kalender',
            'priority' => 'tinggi',
            'expiry_date' => '2025-07-31',
            'attachment' => null,
            'is_active' => 1
        ],
        [
            'title' => 'Prosedur Pengelolaan Dana Sekolah yang Transparan',
            'content' => 'Demi mewujudkan tata kelola keuangan sekolah yang transparan dan akuntabel, SD Cerdas Ceria menetapkan prosedur sebagai berikut:

A. PENERIMAAN DANA:
1. Semua dana yang masuk harus dicatat dalam buku kas harian
2. Setiap penerimaan disertai bukti yang valid
3. Laporan keuangan dibuat setiap bulan
4. Publikasi laporan keuangan di website sekolah

B. PENGELUARAN DANA:
1. Setiap pengeluaran harus ada otorisasi kepala sekolah
2. Pembelian di atas Rp 1.000.000 harus melalui tender terbuka
3. Semua nota/kwitansi disimpan sebagai arsip
4. Laporan pengeluaran diserahkan ke komite sekolah

C. PENGAWASAN:
1. Audit internal setiap 3 bulan
2. Audit eksternal setiap tahun
3. Laporan audit dipublikasikan kepada orang tua siswa

Prosedur ini berlaku untuk semua unit di SD Cerdas Ceria.',
            'type' => 'prosedur',
            'priority' => 'tinggi',
            'expiry_date' => null,
            'attachment' => null,
            'is_active' => 1
        ],
        [
            'title' => 'Pakta Integritas SD Cerdas Ceria 2024',
            'content' => 'PAKTA INTEGRITAS SD CERDAS CERIA

Kami seluruh civitas akademika SD Cerdas Ceria dengan ini menyatakan komitmen untuk:

1. JUJUR dalam segala tindakan dan perkataan
   - Tidak menyontek saat ujian atau tugas
   - Mengakui kesalahan yang telah diperbuat
   - Mengembalikan barang yang bukan milik kita

2. BERTANGGUNG JAWAB atas semua perbuatan
   - Mengerjakan tugas dengan kemampuan sendiri
   - Menjaga kebersihan lingkungan sekolah
   - Datang tepat waktu ke sekolah

3. TRANSPARAN dalam komunikasi
   - Menyampaikan informasi dengan benar
   - Tidak menyembunyikan hal-hal penting
   - Terbuka menerima kritik dan saran

4. ADIL dalam berinteraksi
   - Tidak membeda-bedakan teman
   - Memberikan kesempatan yang sama untuk semua
   - Menegakkan aturan tanpa pandang bulu

Pakta ini ditandatangani oleh seluruh siswa, guru, staff, dan orang tua sebagai komitmen bersama membangun sekolah yang berintegritas.',
            'type' => 'dokumen',
            'priority' => 'tinggi',
            'expiry_date' => null,
            'attachment' => null,
            'is_active' => 1
        ],
        [
            'title' => 'Pengumuman Kompetisi "Aku Anak Jujur" 2024',
            'content' => 'SD Cerdas Ceria mengadakan kompetisi "Aku Anak Jujur" untuk seluruh siswa dengan ketentuan:

KATEGORI LOMBA:
1. Poster Anti Korupsi (Kelas 1-3)
2. Puisi Integritas (Kelas 4-6)  
3. Drama Pendek Nilai-Nilai Kejujuran (Kelas 5-6)
4. Komik Digital Anti Korupsi (Kelas 4-6)

JADWAL:
- Pendaftaran: 1-15 Maret 2024
- Pengumpulan karya: 16-30 Maret 2024
- Penjurian: 1-7 April 2024
- Pengumuman pemenang: 10 April 2024

HADIAH:
- Juara 1: Piala + Sertifikat + Uang pembinaan Rp 500.000
- Juara 2: Piala + Sertifikat + Uang pembinaan Rp 300.000  
- Juara 3: Piala + Sertifikat + Uang pembinaan Rp 200.000

Daftar ke wali kelas masing-masing. Mari tunjukkan kreativitasmu!',
            'type' => 'pengumuman',
            'priority' => 'sedang',
            'expiry_date' => '2024-04-15',
            'attachment' => null,
            'is_active' => 1
        ],
        [
            'title' => 'Prosedur Kantin Kejujuran SD Cerdas Ceria',
            'content' => 'Kantin Kejujuran merupakan salah satu program pendidikan karakter di SD Cerdas Ceria. Prosedur pengoperasiannya:

UNTUK SISWA:
1. Ambil makanan/minuman yang diinginkan
2. Hitung total harga dengan benar
3. Masukkan uang sesuai jumlah yang tertera
4. Ambil kembalian jika ada (jika uang lebih)
5. Tidak mengambil kembalian lebih dari yang seharusnya

UNTUK GURU PENGAWAS:
1. Melakukan pengawasan tidak langsung
2. Mencatat transaksi setiap hari
3. Menghitung saldo kas harian
4. Melaporkan kepada kepala sekolah mingguan

UNTUK PENGELOLA:
1. Menyediakan makanan/minuman berkualitas
2. Mencantumkan harga dengan jelas
3. Menyediakan kembalian uang receh
4. Membersihkan area kantin setiap hari

EVALUASI:
- Dihitung tingkat kejujuran berdasarkan selisih kas
- Jika surplus, akan digunakan untuk kegiatan sosial
- Jika defisit, akan dilakukan pendampingan intensif

Kantin kejujuran buka setiap hari pukul 09.00-15.00.',
            'type' => 'prosedur',
            'priority' => 'sedang',
            'expiry_date' => null,
            'attachment' => null,
            'is_active' => 1
        ],
        [
            'title' => 'Laporan Kegiatan Anti Korupsi Triwulan I 2024',
            'content' => 'LAPORAN KEGIATAN PENDIDIKAN ANTI KORUPSI
SD CERDAS CERIA - TRIWULAN I 2024

I. PROGRAM YANG TELAH DILAKSANAKAN:

A. Pendidikan Karakter Integritas
   - 120 siswa mengikuti workshop "Jujur itu Hebat"
   - 25 guru mengikuti pelatihan anti korupsi
   - 15 orang tua mengikuti seminar parenting integritas

B. Kantin Kejujuran
   - Tingkat kejujuran: 95% (sangat baik)
   - Surplus kas: Rp 50.000 (untuk dana sosial)
   - Partisipasi siswa: 80% dari total siswa

C. Kampanye Anti Korupsi
   - 20 poster dipajang di area sekolah
   - 5 video edukasi ditayangkan setiap pagi
   - 100% kelas memiliki sudut integritas

II. CAPAIAN INDIKATOR:
✓ Peningkatan pemahaman anti korupsi: 85%
✓ Penerapan nilai kejujuran: 90%
✓ Keterlibatan orang tua: 70%
✓ Implementasi transparansi: 95%

III. TINDAK LANJUT:
- Intensifikasi program untuk triwulan II
- Peningkatan keterlibatan orang tua
- Pengembangan modul pembelajaran digital',
            'type' => 'dokumen',
            'priority' => 'sedang',
            'expiry_date' => null,
            'attachment' => null,
            'is_active' => 1
        ]
    ];
    
    echo "Menambahkan " . count($sample_infos) . " data sample informasi umum...\n\n";
    
    $success_count = 0;
    foreach ($sample_infos as $index => $info_data) {
        try {
            $generalInfo->title = $info_data['title'];
            $generalInfo->content = $info_data['content'];
            $generalInfo->type = $info_data['type'];
            $generalInfo->priority = $info_data['priority'];
            $generalInfo->expiry_date = $info_data['expiry_date'];
            $generalInfo->attachment = $info_data['attachment'];
            $generalInfo->is_active = $info_data['is_active'];
            
            if ($generalInfo->create()) {
                $success_count++;
                echo "✅ " . ($index + 1) . ". " . $info_data['title'] . "\n";
                echo "   Tipe: " . $generalInfo->getTypeName($info_data['type']) . "\n";
                echo "   Prioritas: " . $generalInfo->getPriorityName($info_data['priority']) . "\n\n";
            } else {
                echo "❌ Gagal menambah: " . $info_data['title'] . "\n\n";
            }
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n\n";
        }
    }
    
    echo str_repeat("=", 60) . "\n";
    echo "📊 RINGKASAN:\n";
    echo "✅ Berhasil ditambahkan: $success_count dari " . count($sample_infos) . " data\n";
    echo "🎯 Data sample informasi umum dengan tema pendidikan anti korupsi telah siap!\n";
    echo "\n";
    echo "🌐 Silahkan akses: http://localhost/sd/admin/info.php\n";
    echo "👤 Login dengan akun admin yang sudah ada\n\n";
    
    // Show statistics
    $type_counts = $generalInfo->countByType();
    $priority_counts = $generalInfo->countByPriority();
    
    echo "📈 STATISTIK DATA:\n";
    echo "Berdasarkan Tipe:\n";
    foreach ($type_counts as $type => $count) {
        echo "  - " . $generalInfo->getTypeName($type) . ": $count\n";
    }
    echo "\nBerdasarkan Prioritas:\n";
    foreach ($priority_counts as $priority => $count) {
        echo "  - " . $generalInfo->getPriorityName($priority) . ": $count\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🎉 Selesai! Halaman Informasi Umum siap digunakan.\n";
?>
