<?php
// Script untuk menambahkan berita-berita tentang kegiatan anti-korupsi

require_once 'includes/functions.php';
require_once 'models/News.php';
require_once 'config/database.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();
$news = new News($db);

// Data berita anti-korupsi
$integrilitas_news = [
    [
        'title' => 'Launching Kantin Kejujuran SD Cerdas Ceria',
        'content' => '<p>SD Cerdas Ceria dengan bangga meluncurkan program <strong>Kantin Kejujuran</strong> sebagai bagian dari implementasi pendidikan anti-korupsi di lingkungan sekolah. Program ini bertujuan untuk menanamkan nilai kejujuran dan tanggung jawab kepada seluruh siswa sejak dini.</p>

<p>Kantin Kejujuran beroperasi dengan sistem pembayaran mandiri, dimana siswa dapat membeli makanan dan minuman tanpa ada penjaga. Setiap siswa diharapkan untuk membayar sesuai harga yang tertera dan mengambil kembalian secukupnya.</p>

<p><strong>Kepala Sekolah, Ibu Sari Wijayanti, M.Pd</strong> mengatakan, "Kantin Kejujuran ini adalah laboratorium karakter bagi anak-anak kita. Di sinilah mereka belajar bahwa kejujuran bukan hanya slogan, tetapi tindakan nyata yang harus dipraktikkan dalam kehidupan sehari-hari."</p>

<p>Dalam minggu pertama operasinya, Kantin Kejujuran menunjukkan hasil yang menggembirakan dengan tingkat kejujuran mencapai 98%. Para siswa antusias berpartisipasi dan merasa bangga dapat dipercaya untuk mengelola transaksi mereka sendiri.</p>

<p>Program ini juga dilengkapi dengan <em>monitoring system</em> sederhana yang melibatkan siswa senior sebagai mentor bagi adik-adik kelas, sehingga terciptanya budaya saling mengingatkan dalam kebaikan.</p>',
        'excerpt' => 'SD Cerdas Ceria meluncurkan Kantin Kejujuran dengan sistem pembayaran mandiri untuk menanamkan nilai kejujuran dan tanggung jawab pada siswa.',
        'category' => 'kegiatan',
        'status' => 'published',
        'is_featured' => 1,
        'author_id' => 1
    ],
    [
        'title' => 'Peringatan Hari Anti Korupsi Sedunia di SD Cerdas Ceria',
        'content' => '<p>Dalam rangka memperingati Hari Anti Korupsi Sedunia (9 Desember), SD Cerdas Ceria mengadakan serangkaian kegiatan edukatif yang melibatkan seluruh siswa, guru, dan orang tua. Tema yang diangkat tahun ini adalah <strong>"Berani Jujur, Bangga Berintegritas"</strong>.</p>

<p>Kegiatan dimulai dengan <strong>upacara bendera khusus</strong> yang dipimpin oleh siswa kelas VI dengan pembacaan ikrar anti-korupsi. Seluruh peserta upacara mengucapkan ikrar bersama untuk berkomitmen menerapkan 9 nilai integritas dalam kehidupan sehari-hari.</p>

<h3>Rangkaian Kegiatan:</h3>
<ul>
<li><strong>Lomba Poster Anti-Korupsi</strong> - Siswa kelas IV-VI membuat poster kreatif tentang pentingnya kejujuran</li>
<li><strong>Drama Pendek "Si Jujur dan Si Curang"</strong> - Pertunjukan siswa kelas V yang mengisahkan pentingnya kejujuran</li>
<li><strong>Game Edukasi "Integrity Quest"</strong> - Permainan interaktif yang mengajarkan nilai-nilai anti-korupsi</li>
<li><strong>Seminar Mini untuk Orang Tua</strong> - Diskusi tentang cara mendukung pendidikan karakter di rumah</li>
</ul>

<p>Salah satu momen paling berkesan adalah ketika <strong>Ananda Rizki, siswa kelas VI</strong>, berbagi pengalamannya: "Saya pernah menemukan dompet di halaman sekolah. Guru mengajarkan bahwa barang yang bukan milik kita harus dikembalikan kepada pemiliknya. Alhamdulillah, dompet itu berhasil saya kembalikan dan pemiliknya sangat berterima kasih."</p>

<p>Kepala Sekolah mengapresiasi antusiasme seluruh warga sekolah dan menegaskan komitmen SD Cerdas Ceria untuk terus menjadi pelopor pendidikan anti-korupsi di tingkat sekolah dasar.</p>',
        'excerpt' => 'SD Cerdas Ceria memperingati Hari Anti Korupsi Sedunia dengan berbagai kegiatan edukatif yang melibatkan siswa, guru, dan orang tua.',
        'category' => 'kegiatan',
        'status' => 'published',
        'is_featured' => 1,
        'author_id' => 1
    ],
    [
        'title' => 'Program "Buddy System" Memperkuat Nilai Kepedulian Antar Siswa',
        'content' => '<p>SD Cerdas Ceria mengimplementasikan program <strong>"Buddy System"</strong> sebagai bagian dari upaya menanamkan nilai kepedulian dan keadilan di lingkungan sekolah. Program ini memadukan siswa kelas tinggi (IV-VI) dengan siswa kelas rendah (I-III) dalam hubungan mentoring yang saling menguntungkan.</p>

<p>Setiap siswa kelas tinggi bertugas sebagai "kakak mentor" bagi satu siswa kelas rendah, dengan tanggung jawab membantu proses adaptasi, pembelajaran, dan pembentukan karakter. Program ini tidak hanya mengajarkan kepedulian, tetapi juga nilai-nilai kepemimpinan dan tanggung jawab.</p>

<h3>Kegiatan Buddy System meliputi:</h3>
<ul>
<li><strong>Morning Greeting</strong> - Kakak mentor menyambut dan mendampingi adik mentee setiap pagi</li>
<li><strong>Reading Time</strong> - Sesi membaca bersama selama 15 menit setiap hari</li>
<li><strong>Character Building Session</strong> - Diskusi ringan tentang nilai-nilai baik setiap seminggu</li>
<li><strong>Fun Learning Activities</strong> - Permainan edukatif yang memperkuat ikatan mentor-mentee</li>
</ul>

<p><strong>Ibu Ratna Dewi, koordinator program</strong> menjelaskan, "Buddy System mengajarkan anak-anak bahwa membantu orang lain adalah kepedulian nyata yang harus ditunjukkan. Kakak-kakak belajar menjadi pemimpin yang peduli, sementara adik-adik belajar menghargai bantuan orang lain."</p>

<p>Hasil evaluasi menunjukkan peningkatan signifikan dalam hal empati, gotong royong, dan rasa percaya diri siswa. <em>"Saya senang punya kakak mentor, dia selalu membantu saya jika ada kesulitan,"</em> ujar Ananda Dina, siswa kelas II.</p>

<p>Program ini juga mendapat apresiasi dari orang tua yang melihat perubahan positif dalam perilaku anak-anak di rumah, khususnya dalam hal kepedulian terhadap sesama anggota keluarga.</p>',
        'excerpt' => 'Program Buddy System di SD Cerdas Ceria memadukan siswa senior dan junior untuk memperkuat nilai kepedulian dan keadilan antar siswa.',
        'category' => 'kegiatan',
        'status' => 'published',
        'is_featured' => 0,
        'author_id' => 1
    ],
    [
        'title' => 'Lomba Bercerita Anti-Korupsi Tingkat Kelas I-III',
        'content' => '<p>Dalam rangka memperkuat pemahaman nilai-nilai integritas di kalangan siswa kelas rendah, SD Cerdas Ceria menyelenggarakan <strong>"Lomba Bercerita Anti-Korupsi"</strong> khusus untuk siswa kelas I, II, dan III. Lomba ini bertujuan untuk mengembangkan kemampuan public speaking sambil menanamkan nilai-nilai kejujuran dan integritas.</p>

<p>Sebanyak 45 siswa dari tiga tingkat kelas berpartisipasi dengan antusias, menceritakan berbagai dongeng dan kisah inspiratif yang mengandung nilai-nilai anti-korupsi. Tema-tema yang diangkat meliputi kejujuran, tanggung jawab, keadilan, dan kepedulian.</p>

<h3>Juara Lomba Bercerita:</h3>
<ul>
<li><strong>Juara 1:</strong> Ananda Faiz Ramadhan (Kelas III) - Cerita "Si Penjual Es Krim yang Jujur"</li>
<li><strong>Juara 2:</strong> Ananda Zahra Aulia (Kelas II) - Cerita "Kotak Pensil yang Hilang"</li>
<li><strong>Juara 3:</strong> Ananda Bintang Pratama (Kelas I) - Cerita "Berbagi Bekal dengan Teman"</li>
</ul>

<p>Yang menarik, setiap peserta tidak hanya dinilai dari kemampuan bercerita, tetapi juga dari pemahaman mereka terhadap nilai-nilai yang terkandung dalam cerita. <strong>Tim juri</strong> yang terdiri dari guru-guru dan pustakawan sekolah memberikan apresiasi tinggi terhadap kreativitas dan pemahaman anak-anak.</p>

<p><em>"Cerita tentang penjual es krim yang tidak mengurangi takaran meski tidak ada yang melihat mengajarkan saya bahwa kejujuran itu penting,"</em> ungkap Ananda Faiz, sang juara pertama.</p>

<p>Kegiatan ini juga dihadiri oleh orang tua yang memberikan dukungan moral kepada anak-anak. <strong>Ibu Sinta Maharani, wali murid</strong> mengapresiasi, "Program seperti ini sangat membantu kami dalam mendidik karakter anak di rumah. Anak-anak jadi lebih mudah memahami konsep kejujuran melalui cerita."</p>

<p>Sebagai tindak lanjut, karya-karya cerita terbaik akan dibukukan dan menjadi bahan bacaan di perpustakaan sekolah sebagai inspirasi bagi siswa lainnya.</p>',
        'excerpt' => 'Lomba Bercerita Anti-Korupsi untuk siswa kelas I-III berhasil mengembangkan kemampuan public speaking sambil menanamkan nilai-nilai integritas.',
        'category' => 'prestasi',
        'status' => 'published',
        'is_featured' => 0,
        'author_id' => 1
    ],
    [
        'title' => 'Workshop "Orang Tua Sebagai Agen Anti-Korupsi"',
        'content' => '<p>SD Cerdas Ceria menyelenggarakan workshop khusus untuk orang tua siswa dengan tema <strong>"Orang Tua Sebagai Agen Anti-Korupsi di Rumah"</strong>. Workshop ini merupakan bagian integral dari program pendidikan karakter sekolah yang melibatkan seluruh elemen komunitas sekolah.</p>

<p>Acara yang dihadiri oleh 120 orang tua siswa ini menghadirkan <strong>Dr. Bambang Widjojanto, pakar pendidikan karakter</strong> dan <strong>Ibu Maya Safira, psikolog anak</strong> sebagai narasumber utama. Workshop berlangsung selama 3 jam dengan sesi presentasi, diskusi, dan praktik langsung.</p>

<h3>Materi Workshop meliputi:</h3>
<ul>
<li><strong>Memahami 9 Nilai Anti-Korupsi</strong> - Penjelasan detail tentang kejujuran, tanggung jawab, disiplin, dll.</li>
<li><strong>Strategi Pendidikan Karakter di Rumah</strong> - Metode praktis untuk orang tua</li>
<li><strong>Konsistensi Nilai antara Sekolah dan Rumah</strong> - Pentingnya sinkronisasi pendidikan</li>
<li><strong>Mengatasi Tantangan dalam Mendidik Karakter</strong> - Tips menghadapi situasi sulit</li>
</ul>

<p>Salah satu sesi yang paling menarik perhatian adalah <strong>role play</strong> dimana orang tua mempraktikkan cara merespons berbagai situasi yang menguji kejujuran anak, seperti ketika anak menemukan uang di jalan atau ketika anak berbohong tentang tugas sekolah.</p>

<p><strong>Pak Ahmad Fauzi, wali murid kelas IV</strong> berbagi pengalaman, <em>"Workshop ini membuka mata saya bahwa mendidik karakter itu tidak cukup dengan nasihat. Anak-anak belajar lebih banyak dari apa yang mereka lihat kita lakukan setiap hari."</em></p>

<p>Workshop juga membahas pentingnya memberikan contoh nyata di rumah, seperti tidak berbohong pada telepon, mengembalikan uang kembalian yang lebih, dan bersikap adil terhadap semua anak.</p>

<p><strong>Hasil evaluasi workshop menunjukkan:</strong></p>
<ul>
<li>95% peserta merasa lebih percaya diri dalam mendidik karakter anak</li>
<li>88% peserta berkomitmen menerapkan strategi yang dipelajari</li>
<li>92% peserta meminta workshop lanjutan dengan tema serupa</li>
</ul>

<p>Kepala Sekolah menegaskan bahwa workshop seperti ini akan diadakan secara rutin setiap semester untuk terus memperkuat sinergi antara pendidikan di sekolah dan di rumah.</p>',
        'excerpt' => 'Workshop untuk orang tua siswa tentang peran mereka sebagai agen anti-korupsi di rumah, memperkuat sinergi pendidikan karakter.',
        'category' => 'kegiatan',
        'status' => 'published',
        'is_featured' => 0,
        'author_id' => 1
    ]
];

echo "<h2>Menambahkan Berita-berita Anti-Korupsi</h2>";

foreach ($integrilitas_news as $index => $newsData) {
    try {
        // Set properties
        $news->title = $newsData['title'];
        $news->slug = createSlug($newsData['title']);
        $news->content = $newsData['content'];
        $news->excerpt = $newsData['excerpt'];
        $news->category = $newsData['category'];
        $news->status = $newsData['status'];
        $news->is_featured = $newsData['is_featured'];
        $news->author_id = $newsData['author_id'];
        $news->featured_image = ''; // No image for now
        
        if ($news->create()) {
            echo "<div style='background: #10B981; color: white; padding: 10px; margin: 10px 0; border-radius: 8px;'>";
            echo "✅ <strong>Berhasil:</strong> " . htmlspecialchars($newsData['title']);
            echo "<br><small>Kategori: " . $newsData['category'] . " | Featured: " . ($newsData['is_featured'] ? 'Ya' : 'Tidak') . "</small>";
            echo "</div>";
        } else {
            echo "<div style='background: #EF4444; color: white; padding: 10px; margin: 10px 0; border-radius: 8px;'>";
            echo "❌ <strong>Gagal:</strong> " . htmlspecialchars($newsData['title']);
            echo "</div>";
        }
        
    } catch (Exception $e) {
        echo "<div style='background: #F59E0B; color: white; padding: 10px; margin: 10px 0; border-radius: 8px;'>";
        echo "⚠️ <strong>Error:</strong> " . $e->getMessage();
        echo "</div>";
    }
    
    // Reset object for next iteration
    $news = new News($db);
}

echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p>Script selesai dijalankan. Silakan cek <a href='news.php'>dashboard berita</a> untuk melihat hasilnya.</p>";
echo "<p>Atau lihat <a href='../berita.html'>halaman berita frontend</a> untuk melihat bagaimana berita ditampilkan.</p>";

echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }";
echo "h2, h3 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }";
echo "a { color: #3B82F6; text-decoration: none; }";
echo "a:hover { text-decoration: underline; }";
echo "</style>";
?>
