# 📋 Dokumentasi Halaman Informasi Umum Admin

## 🎯 Overview
Halaman **Informasi Umum** (`admin/info.php`) adalah fitur pengelolaan konten informasi umum sekolah yang mencakup pengumuman, kalender akademik, prosedur & SOP, dan dokumen penting dengan tema pendidikan anti korupsi.

## 🏗️ Struktur File
```
admin/
├── info.php                    # Main admin page
├── info_ajax.php               # AJAX handler for modals
├── models/GeneralInfo.php      # Model class for CRUD operations
├── uploads/attachments/        # Directory for file attachments
├── add_sample_info.php         # Script to add sample data
└── test_info.php              # Test script
```

## 📊 Database Structure
Menggunakan tabel `general_info` dengan struktur:
- `id` (Primary Key)
- `title` (VARCHAR 255) - Judul informasi
- `content` (TEXT) - Konten informasi
- `type` (ENUM) - Jenis: pengumuman, kalender, prosedur, dokumen
- `priority` (ENUM) - Prioritas: tinggi, sedang, rendah
- `expiry_date` (DATE) - Tanggal kedaluwarsa (optional)
- `attachment` (VARCHAR 255) - File lampiran (optional)
- `is_active` (BOOLEAN) - Status aktif
- `created_at` & `updated_at` (TIMESTAMP)

## ✨ Fitur Utama

### 1. Dashboard Overview
- **Statistik Cards**: Menampilkan jumlah per tipe (Pengumuman, Kalender, Prosedur, Dokumen)
- **Alert Kedaluwarsa**: Notifikasi untuk informasi yang telah expired
- **Quick Actions**: Tombol cepat untuk menambah informasi baru

### 2. Filter & Search
- **Search Box**: Pencarian berdasarkan judul atau konten
- **Filter Tipe**: Pengumuman, Kalender Akademik, Prosedur & SOP, Dokumen Penting
- **Filter Prioritas**: Tinggi, Sedang, Rendah
- **Pagination**: Navigasi halaman dengan 10 data per halaman

### 3. CRUD Operations
- **Create**: Tambah informasi baru dengan form lengkap
- **Read**: View detail informasi dalam modal
- **Update**: Edit informasi existing
- **Delete**: Hapus informasi dengan konfirmasi

### 4. File Management
- **Upload Lampiran**: Mendukung PDF, DOC, DOCX, XLS, XLSX (max 5MB)
- **Download**: Link download untuk file attachment
- **Auto Delete**: File terhapus otomatis saat data dihapus

## 🎨 UI/UX Features

### Design Elements
- **Color-coded Priority**:
  - Tinggi: Merah (`bg-red-100 text-red-800`)
  - Sedang: Kuning (`bg-yellow-100 text-yellow-800`)
  - Rendah: Hijau (`bg-green-100 text-green-800`)

- **Type Icons**:
  - Pengumuman: `fas fa-bullhorn`
  - Kalender: `fas fa-calendar-alt`
  - Prosedur: `fas fa-list-ol`
  - Dokumen: `fas fa-file-alt`

### Interactive Elements
- **Modal Forms**: Create/Edit dalam modal responsif
- **AJAX Loading**: Fetch data tanpa reload halaman
- **Hover Effects**: Visual feedback pada table rows
- **Status Indicators**: Badge untuk status aktif/nonaktif dan expired

## 📋 Data Sample
Telah dilengkapi dengan 7 data sample bertema **Pendidikan Anti Korupsi**:

1. **Pengumuman** (2):
   - Kegiatan Pendidikan Integritas Semester Genap 2024
   - Kompetisi "Aku Anak Jujur" 2024

2. **Kalender** (1):
   - Kalender Akademik 2024/2025 - Tema Pendidikan Anti Korupsi

3. **Prosedur** (2):
   - Prosedur Pengelolaan Dana Sekolah yang Transparan
   - Prosedur Kantin Kejujuran SD Cerdas Ceria

4. **Dokumen** (2):
   - Pakta Integritas SD Cerdas Ceria 2024
   - Laporan Kegiatan Anti Korupsi Triwulan I 2024

## 🔧 Technical Features

### Model Class Methods (`GeneralInfo.php`)
```php
// CRUD Operations
getAll($limit, $offset, $search, $type, $priority)
getById($id)
getActive($limit, $offset, $type, $priority)
create()
update()
delete()

// Helper Methods
getByType($type)
getExpired()
validate($data)
countByType()
countByPriority()

// Display Helpers
getTypeName($type)
getPriorityName($priority)
getPriorityBadgeClass($priority)
getTypeIcon($type)
isExpired($expiry_date)
```

### AJAX Endpoints (`info_ajax.php`)
- `GET /info_ajax.php?action=get&id={id}` - Fetch info data for editing
- `GET /info_ajax.php?action=view&id={id}` - Get formatted view content

### Form Validation
- Required fields: title, content, type
- Date format validation untuk expiry_date
- File type validation untuk attachments
- XSS protection dengan htmlspecialchars()

## 🛡️ Security Features
- **Authentication**: Requires admin login
- **CSRF Protection**: Untuk form submissions (dapat diimplementasikan)
- **File Upload Security**: Type dan size validation
- **SQL Injection Prevention**: Prepared statements
- **XSS Prevention**: Output escaping

## 📱 Responsive Design
- **Mobile-First**: Adaptif untuk semua screen size
- **Grid Layout**: Responsive statistics cards
- **Modal Responsiveness**: Modal forms yang mobile-friendly
- **Table Responsive**: Horizontal scroll pada tabel di mobile

## 🚀 Installation & Usage

### 1. Setup
```bash
# Buat direktori uploads
mkdir -p admin/uploads/attachments

# Set permissions (Linux/Mac)
chmod 755 admin/uploads/attachments

# Import database structure (sudah ada di database_setup.sql)
```

### 2. Add Sample Data
```bash
php admin/add_sample_info.php
```

### 3. Test
```bash
php admin/test_info.php
```

### 4. Access
- URL: `http://localhost/sd/admin/info.php`
- Login dengan credentials admin yang sudah ada

## 🔄 Integration Points

### Frontend Integration
Data informasi dapat ditampilkan di frontend dengan:
```php
// Untuk menampilkan pengumuman di homepage
$announcements = $generalInfo->getByType('pengumuman');

// Untuk kalender akademik
$calendar = $generalInfo->getByType('kalender');

// Hanya yang aktif dan belum expired
$active_info = $generalInfo->getActive(5, 0, 'pengumuman');
```

### Admin Dashboard Integration
- Link sudah tersedia di sidebar: "Informasi Umum"
- Statistics dapat ditambahkan ke dashboard utama
- Notification untuk expired content

## 📈 Analytics & Monitoring
- Tracking jumlah view per informasi (dapat ditambahkan)
- Expired content monitoring
- File download analytics (dapat ditambahkan)
- User engagement metrics (dapat ditambahkan)

## 🎓 Educational Value
Halaman ini mendukung **Pendidikan Anti Korupsi** melalui:
- ✅ Transparansi informasi sekolah
- ✅ Prosedur yang jelas dan terbuka
- ✅ Dokumentasi kegiatan integritas
- ✅ Kalender akademik yang akuntabel
- ✅ Sistem pengelolaan yang tertib

## 🏆 Best Practices Applied
1. **MVC Pattern**: Model-View-Controller separation
2. **DRY Principle**: Reusable helper methods
3. **Security First**: Input validation & sanitization
4. **User Experience**: Intuitive interface design
5. **Performance**: Efficient database queries with pagination
6. **Maintainability**: Clean, documented code
7. **Accessibility**: Semantic HTML structure
8. **Responsiveness**: Mobile-friendly design

---

**Status**: ✅ **COMPLETED & READY TO USE**
**Last Updated**: September 8, 2025
**Version**: 1.0.0
