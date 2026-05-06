# ⚡ QUICK START - RS Hermana Makassar

Panduan cepat setup website RS Hermana dalam 5 menit!

## 📋 Requirement

- PHP 7.4+
- MySQL 5.7+
- Apache dengan mod_rewrite (atau Nginx)
- Browser modern (Chrome, Firefox, Safari, Edge)

## 🚀 Setup Cepat (5 Langkah)

### Step 1️⃣ : Buat Database

**Menggunakan Command Line:**
```bash
mysql -u root -p < database.sql
```

**Atau di phpMyAdmin:**
1. Buka http://localhost/phpmyadmin
2. Login dengan user MySQL
3. Klik "Import"
4. Pilih file `database.sql`
5. Klik "Go"

### Step 2️⃣ : Copy File ke Webroot

**Windows (XAMPP):**
```
C:\xampp\htdocs\rs_hermana\
```
Copy semua .php file ke folder ini

**Linux/Mac (Apache):**
```
/var/www/html/rs_hermana/
```

### Step 3️⃣ : Setup Folder Permissions (Linux/Mac)

```bash
chmod -R 755 /var/www/html/rs_hermana/
chmod -R 777 /var/www/html/rs_hermana/
```

### Step 4️⃣ : Update Config (Jika Perlu)

Edit file `config.php`:

```php
$host = "localhost";      // Hostname MySQL
$user = "root";           // Username MySQL
$password = "";           // Password (jika ada)
$database = "rs_hermana"; // Nama database
```

**Tips:**
- Jika MySQL user bukan `root`, sesuaikan username
- Jika ada password MySQL, masukkan di `$password`

### Step 5️⃣ : Akses Website

Buka browser dan kunjungi:

```
http://localhost/rs_hermana/
```

✅ Selesai! Website siap digunakan.

---

## 🔓 Login Dengan Akun Demo

### Akun Pasien
```
NIK (Username)  : 1234567890123456
Password        : password123
Nama            : Ahmad Siregar
```

### Akun Dokter
```
NIK (Username)  : 1111111111111111
Password        : dokter123
Nama            : Dr. Siti Nurhaliza
Poli            : Anak / Pediatri
```

### Akun Dokter 2
```
NIK (Username)  : 2222222222222222
Password        : dokter123
Nama            : Dr. Budi Santoso
Poli            : Gigi / Spesialistik
```

---

## 🗂️ Struktur File Penting

```
rs_hermana/
├── index.php              👈 Halaman utama (landing page)
├── login.php              👈 Login page
├── register.php           👈 Register page
├── config.php             👈 Database config (EDIT JIKA PERLU!)
│
├── PASIEN PORTAL
├── dashboard_pasien.php
├── pelayanan.php
├── rawat_inap.php
├── farmasi.php
├── akun.php
│
├── DOKTER PORTAL
├── dashboard_dokter.php
├── monitoring_pasien.php
├── resep_digital.php
├── apotek_stok.php
│
└── database.sql           👈 Database schema
```

---

## 🧪 Testing Fitur

### Pasien - Workflow Lengkap
1. Login sebagai pasien (1234567890123456 / password123)
2. Lihat dashboard → "Akses Cepat" → Pelayanan
3. Daftar rawat inap → Pilih kamar → Submit
4. Lihat farmasi → Cek obat tersedia → Minta obat
5. Manajemen akun → Edit data → Ganti password

### Dokter - Workflow Lengkap
1. Login sebagai dokter (1111111111111111 / dokter123)
2. Lihat dashboard → Monitoring pasien
3. Klik "Edit" pasien → Update kondisi
4. Input resep → Pilih pasien → Pilih obat → Submit
5. Manajemen apotek → Lihat stok → Tambah stok

---

## 🎨 Fitur Bonus

✅ **Dark Mode**
- Klik icon matahari/bulan di navbar
- Theme disimpan di session
- Responsif di semua halaman

✅ **Responsive Design**
- Test di mobile & desktop
- Sidebar otomatis hidden di mobile
- All forms mobile-friendly

✅ **Smooth UI**
- Hover effects pada tombol
- Smooth scroll
- Color-coded status

---

## ⚠️ Troubleshooting Cepat

### ❌ "Koneksi gagal"
```
→ Cek MySQL sudah running
→ Cek user/password di config.php
→ Cek database rs_hermana sudah dibuat
```

### ❌ "Login tidak bekerja"
```
→ Clear browser cookies
→ Check browser console (F12) untuk error
→ Pastikan session folder punya permission write
```

### ❌ "Halaman blank/error"
```
→ Check PHP error log
→ Enable error_reporting di config.php
→ Test koneksi database
```

### ❌ "Dark mode tidak bekerja"
```
→ Clear browser cache (Ctrl+Shift+Del)
→ Update browser ke versi terbaru
→ Check javascript console untuk error
```

---

## 📱 Device Testing

Tested dan working di:
- ✅ Desktop (Windows, Mac, Linux)
- ✅ Android (Chrome Mobile)
- ✅ iPhone (Safari)
- ✅ Tablet (iPad, Android Tablet)

---

## 🔐 Security Reminder

⚠️ **PENTING**: Sistem ini adalah DEMO/PEMBELAJARAN

Sebelum production, lakukan:
- [ ] Hash password dengan bcrypt
- [ ] Use prepared statements
- [ ] Add CSRF protection
- [ ] Enable HTTPS
- [ ] Setup proper folder permissions
- [ ] Hide sensitive files (.env, config)

---

## 📚 File Dokumentasi Lengkap

- `README.md` - Dokumentasi lengkap
- `QUICK_START.md` - File ini (quick start)
- `database.sql` - Schema dan dummy data

---

## 💡 Tips & Tricks

1. **Tambah Data Dummy**
   - Edit `database.sql` untuk menambah user, dokter, obat
   - Re-import ke database

2. **Ganti Warna**
   - Edit tailwind class di file .php
   - Ganti `purple-600` dengan warna lain (blue, green, red)

3. **Customize Kontak**
   - Edit nomor WhatsApp di `index.php`
   - Edit Instagram di `index.php`
   - Edit alamat di `index.php`

4. **Backup Database**
   ```bash
   mysqldump -u root -p rs_hermana > backup.sql
   ```

---

## 🎯 Next Steps

Setelah setup berhasil:

1. **Explore Portal Pasien**
   - Pahami alur registrasi rawat inap
   - Test minta obat
   - Edit data akun

2. **Explore Portal Dokter**
   - Monitor pasien
   - Input resep
   - Manage stok obat

3. **Customize**
   - Ganti warna tema
   - Tambah data dokter/obat
   - Modify form sesuai kebutuhan

4. **Deploy**
   - Upload ke hosting
   - Setup domain
   - Enable SSL/HTTPS

---

## 📞 Support

Jika ada error:
1. Check README.md untuk dokumentasi lengkap
2. Check browser console (F12 → Console)
3. Check PHP error log
4. Read code comments di file PHP

---

**Happy Coding! 🚀**

Website RS Hermana siap untuk learning & development! 🏥

