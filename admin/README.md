# Dashboard Admin SD Integra IV

Dashboard backend untuk mengelola konten website SD Integra IV menggunakan PHP dan Tailwind CSS.

## 📋 Fitur Utama

- **Dashboard Statistik**: Overview data dan activity terbaru
- **Manajemen Berita**: CRUD berita dengan kategori, status, dan featured posts
- **Program Akademik**: Kelola kurikulum dan program pembelajaran
- **Informasi Umum**: Pengumuman, kalender, dan dokumen penting
- **Inovasi Pembelajaran**: Showcase metode dan teknologi terbaru
- **Pesan Kontak**: Kelola pesan dari form kontak website
- **Galeri Media**: Upload dan organize gambar/dokumen
- **Pengaturan Website**: Konfigurasi data sekolah
- **Manajemen User**: Admin user management dengan role-based access

## 🛠️ Instalasi

### 1. Setup Database
```bash
# Import database structure
mysql -u root -p < admin/config/database_setup.sql
```

### 2. Konfigurasi Database
Edit file `admin/config/database.php` sesuai dengan setting MySQL Anda:
```php
private $host = "localhost";
private $db_name = "sd_integra_iv";
private $username = "root";
private $password = "your_password";
```

### 3. Setup File Permissions
```bash
# Buat direktori upload dengan permission yang tepat
mkdir uploads
chmod 755 uploads
```

### 4. Web Server
Gunakan salah satu cara berikut untuk menjalankan:

#### Apache/Nginx
- Copy seluruh folder ke document root
- Akses via browser: `http://localhost/elementary-school-website/admin/`

#### PHP Built-in Server
```bash
# Dari root directory project
php -S localhost:8000

# Akses admin: http://localhost:8000/admin/
```

## 🔐 Login Default

**Username:** `admin`  
**Password:** `admin123`

## 📁 Struktur Direktori

```
admin/
├── config/           # Database configuration
├── controllers/      # Business logic handlers
├── models/          # Database models
├── views/           # HTML templates
├── includes/        # Common files (header, footer, functions)
├── assets/          # CSS, JS, images
└── uploads/         # File uploads
```

## 🌐 API Endpoints

Dashboard menyediakan API untuk frontend:

### News API
```
GET /api/news.php?action=list&page=1&limit=10
GET /api/news.php?action=featured&limit=5
GET /api/news.php?action=detail&slug=berita-slug
GET /api/news.php?action=categories
```

### Response Format
```json
{
    "success": true,
    "data": [...],
    "pagination": {
        "current_page": 1,
        "total_pages": 5,
        "total_records": 50,
        "per_page": 10
    }
}
```

## 📝 Penggunaan

### 1. Manajemen Berita
- **Tambah Berita**: Klik "Tambah Berita" di dashboard atau menu Berita
- **Kategori**: Umum, Prestasi, Kegiatan, Pengumuman
- **Status**: Draft, Published, Archived
- **Featured**: Tandai berita penting untuk ditampilkan di halaman utama
- **Auto Slug**: Slug dibuat otomatis dari judul

### 2. Program Akademik
- Kelola kurikulum per tingkat kelas (1-6)
- Jenis kurikulum: Nasional, Internasional, Muatan Lokal
- Data mata pelajaran dan metode pembelajaran dalam format JSON

### 3. Upload Media
- Supported formats: JPG, PNG, GIF, PDF, DOC, DOCX
- Automatic file naming dengan timestamp
- Preview untuk gambar

### 4. Pengaturan Website
- Data sekolah (nama, alamat, kontak)
- Social media links
- Statistik (jumlah siswa, guru, dll)

## 🔧 Kustomisasi

### Menambah Field Baru
1. Update database schema
2. Modify model class di `models/`
3. Update form di views
4. Adjust validation rules

### Styling
Dashboard menggunakan Tailwind CSS via CDN. Untuk kustomisasi:
- Edit konfigurasi Tailwind di `includes/admin_header.php`
- Tambah custom CSS di section `<style>`

### Role-based Access
```php
// Contoh pembatasan akses
if ($_SESSION['admin_role'] !== 'super_admin') {
    header('Location: unauthorized.php');
    exit;
}
```

## 🐛 Troubleshooting

### Database Connection Error
- Pastikan MySQL service running
- Check database credentials di `config/database.php`
- Verify database exists dan user memiliki akses

### File Upload Issues
- Check folder `uploads/` permissions (755)
- Verify PHP `upload_max_filesize` dan `post_max_size`
- Ensure disk space available

### Session Issues
- Check PHP session configuration
- Verify session directory writable
- Clear browser cookies jika diperlukan

## 📊 Performance Tips

- Enable PHP OPcache untuk production
- Use proper database indexing
- Implement file-based caching untuk static content
- Optimize images sebelum upload
- Regular database cleanup untuk old records

## 🔒 Security Considerations

- Selalu update password default
- Implement HTTPS untuk production
- Regular backup database
- Sanitize all user inputs
- Use prepared statements (sudah implemented)
- Implement rate limiting untuk login attempts

## 📱 Mobile Responsiveness

Dashboard fully responsive dengan breakpoints:
- Mobile: < 768px
- Tablet: 768px - 1024px  
- Desktop: > 1024px

## 🤝 Contributing

1. Fork repository
2. Create feature branch
3. Test thoroughly
4. Submit pull request dengan dokumentasi

## 📞 Support

Untuk bantuan teknis atau pertanyaan, hubungi tim development atau buat issue di repository.
