# 🏥 Website RS Hermana Makassar

Sistem manajemen rumah sakit digital terpadu dengan portal pasien dan dokter.

## 📋 Fitur Utama

### 🌐 Area Publik
- Landing Page responsif dengan hero section
- Informasi lengkap tentang RS (Tentang, Fasilitas, Layanan)
- Daftar dokter dan spesialisasi
- Embed Google Maps lokasi
- Dark Mode toggle responsif
- Footer dengan kontak WhatsApp & Instagram

### 👤 Sistem Autentikasi
- Login & Register dengan NIK
- Session-based authentication
- Password management (ganti password)
- Role-based access (Pasien & Dokter)

### 🏥 Portal Pasien
- **Dashboard Pasien**: Overview statistik dan akses cepat
- **Daftar Dokter**: Lihat semua dokter, poli, spesialisasi, jam praktik
- **Registrasi Rawat Inap**: Daftar kamar (IGD, VVIP, VIP, Kelas 1-3)
- **Farmasi**: 
  - Cek daftar obat tersedia
  - Minta obat (dengan status pending/disetujui/ditolak)
  - Lihat resep aktif
  - Riwayat permintaan obat
- **Manajemen Akun**:
  - Edit data pribadi (nama, alamat)
  - Ganti password
  - Lihat informasi akun

### 👨‍⚕️ Dashboard Dokter
- **Dashboard Dokter**: Overview pasien dan statistik
- **Monitoring Pasien**: 
  - Daftar pasien rawat inap aktif
  - Update kondisi pasien
  - Update status (aktif/selesai/batal)
- **Input Resep Digital**:
  - Buat resep untuk pasien
  - Pilih obat, dosis, durasi
  - Tambah catatan khusus
  - Lihat riwayat resep
- **Manajemen Apotek**:
  - Lihat stok semua obat
  - Tambah/kurangi stok
  - Alert stok rendah (<10)

## 🛠️ Teknologi

- **Backend**: PHP 7.4+ (Native - Tanpa Framework)
- **Database**: MySQL 5.7+
- **Frontend**: Tailwind CSS v3 (CDN)
- **Authentication**: Session-based
- **Server**: Apache dengan mod_rewrite

## 📦 Struktur File

```
rs_hermana/
├── index.php              # Landing Page
├── login.php              # Login Page
├── register.php           # Register Page
├── logout.php             # Logout handler
│
├── config.php             # Database config & utilities
├── theme.php              # Dark mode toggle
├── header_dashboard.php   # Dashboard header component
│
├── PORTAL PASIEN
├── dashboard_pasien.php   # Dashboard utama pasien
├── pelayanan.php          # Daftar dokter & layanan
├── rawat_inap.php         # Registrasi rawat inap
├── farmasi.php            # Manajemen farmasi
├── akun.php               # Manajemen akun pasien
│
├── PORTAL DOKTER
├── dashboard_dokter.php   # Dashboard utama dokter
├── monitoring_pasien.php  # Monitoring pasien rawat inap
├── resep_digital.php      # Input resep digital
├── apotek_stok.php        # Manajemen stok obat
│
├── .htaccess              # Apache configuration
├── database.sql           # Database schema & dummy data
└── README.md              # Dokumentasi ini
```

## 🚀 Cara Instalasi

### 1. Setup Database

```bash
# Login ke MySQL
mysql -u root -p

# Run SQL script
source database.sql;
```

Atau copy-paste isi file `database.sql` ke phpMyAdmin.

### 2. Download & Setup File

1. Download semua file PHP ke folder project
2. Letakkan di `/var/www/html/rs_hermana` (Linux/Mac)
   atau `C:\xampp\htdocs\rs_hermana` (Windows)

### 3. Update Config Database

Edit `config.php`:
```php
$host = "localhost";      // Host MySQL
$user = "root";           // Username MySQL
$password = "";           // Password MySQL (kosong jika tidak ada)
$database = "rs_hermana"; // Nama database
```

### 4. Akses Website

- **Landing Page**: http://localhost/rs_hermana/
- **Login**: http://localhost/rs_hermana/login.php
- **Register**: http://localhost/rs_hermana/register.php

## 👤 Akun Demo (Default)

### Pasien
- **Username (NIK)**: 1234567890123456
- **Password**: password123
- **Nama**: Ahmad Siregar

### Dokter
- **Username (NIK)**: 1111111111111111
- **Password**: dokter123
- **Nama**: Dr. Siti Nurhaliza (Poli Anak)

### Dokter 2
- **Username (NIK)**: 2222222222222222
- **Password**: dokter123
- **Nama**: Dr. Budi Santoso (Poli Gigi)

## 🎨 Desain & Theme

- **Warna Utama**: Purple (#7c3aed)
- **Secondary Colors**: Blue, Green, Red (untuk status)
- **Footer**: Bottle Green (#0f3d2e)
- **Responsive**: Mobile-first dengan Tailwind Grid/Flex
- **Dark Mode**: Toggle tersedia di navbar (bernyanyi matahari ☀️/bulan 🌙)

## 🔐 Keamanan

⚠️ **Catatan Penting**: Sistem ini TIDAK menggunakan enkripsi tingkat tinggi atau proteksi SQL Injection yang ketat. Ini adalah demo/pembelajaran. Untuk production:

- [ ] Gunakan password hashing (bcrypt/argon2)
- [ ] Implement prepared statements untuk SQL
- [ ] Add CSRF tokens
- [ ] Implement rate limiting
- [ ] Use HTTPS/SSL
- [ ] Add input validation lebih ketat

## 📝 Catatan Fitur

### Dark Mode
- Toggle tersedia di navbar (icon matahari/bulan)
- Menggunakan session-based theme
- Responsif di semua halaman

### Responsive Design
- Mobile-first approach
- Tested di Android & Desktop
- Sidebar sidebar hanya tampil di lg screen
- Hamburger menu bisa ditambahkan untuk mobile

### Database Features
- Foreign keys untuk relasi
- Timestamps (created_at)
- Enum untuk status
- Indexes untuk performa

## 🐛 Troubleshooting

### Database tidak terkoneksi
```
Error: "Koneksi gagal"
```
- Cek username/password di `config.php`
- Pastikan MySQL running
- Cek database sudah dibuat

### Session tidak bekerja
```
Error: "Redirect loop"
```
- Pastikan session_start() di awal setiap file
- Cek folder /tmp punya permission write
- Clear browser cookies

### Dark mode tidak berfungsi
```
Theme tidak berubah
```
- Clear browser cache
- Check javascript console untuk error
- Pastikan fetch API support (modern browser)

## 📞 Kontak RS (Dalam System)

- **WhatsApp**: 0895-3246-70592
- **Instagram**: @mazisasmara
- **Alamat**: Jl. Desa Amegakure, Makassar City, South Sulawesi

## 📄 License

Untuk educational & development purposes.

## 🙏 Catatan Pengembang

Website ini dibuat dengan:
- PHP Native (tanpa framework)
- Tailwind CSS CDN
- MySQL
- Fokus pada fungsionalitas & UX

Cocok untuk:
- ✅ Learning PHP & Web Development
- ✅ Prototyping & MVP
- ✅ Hospital Management System Demo
- ❌ Production (perlu security hardening)

---

**Versi**: 1.0  
**Terakhir Update**: 2026-05-01  
**Dibuat untuk**: RS Hermana Makassar
