# Tool System - Sistem Manajemen Peminjaman Alat

Tool System adalah aplikasi berbasis web yang dirancang untuk memfasilitasi manajemen inventaris dan proses peminjaman alat di lingkungan sekolah atau instansi (UKK Project). Aplikasi ini memiliki desain bertema **Industrial** yang modern, bersih, dan responsif.

## 🚀 Fitur Utama

### 1. Multi-Role Authentication

Sistem mendukung tiga peran pengguna dengan hak akses yang berbeda:

- **Admin**: Akses penuh ke seluruh sistem, manajemen user, kategori, alat, dan laporan.
- **Petugas**: Bertanggung jawab atas verifikasi peminjaman, proses pengembalian, dan manajemen stok.
- **Peminjam**: Dapat melihat katalog alat, mengajukan peminjaman, dan memantau riwayat peminjaman mereka.

### 2. Manajemen Inventaris

- CRUD (Create, Read, Update, Delete) Alat dan Kategori.
- Pelacakan stok alat secara real-time.
- Status ketersediaan alat otomatis.
- Upload gambar alat.

### 3. Alur Peminjaman & Pengembalian

- Pengajuan peminjaman oleh Peminjam.
- Sistem persetujuan (Approve/Reject) oleh Petugas/Admin.
- Perhitungan otomatis denda keterlambatan.
- Manajemen denda kerusakan alat.

### 4. Pelaporan & Audit

- **Laporan PDF**: Ekspor laporan peminjaman dan pengembalian dalam periode tertentu.
- **Activity Logs**: Mencatat setiap perubahan data penting untuk keamanan dan transparansi.
- **Dashboard Statistik**: Visualisasi data operasional dalam bentuk chart dan kartu info.

### 5. Fitur Tambahan

- **Responsive Design**: Optimal untuk tampilan desktop maupun perangkat mobile.
- **Industrial UI**: Antarmuka pengguna yang premium dengan micro-animations dan dark mode support.
- **E-Receipt**: Cetak bukti peminjaman dan pengembalian.

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL
- **Frontend**: Blade Templating, Vanilla CSS (Custom UI Framework)
- **Library**:
  - DomPDF (untuk laporan)
  - Chart.js (untuk statistik)
  - Google Fonts (Outfit & JetBrains Mono)
  - Material Symbols (Icons)

## ⚙️ Instalasi

1. Clone repository:

   ```bash
   git clone https://github.com/username/tool-system.git
   ```

2. Install dependencies:

   ```bash
   composer install
   npm install
   ```

3. Copy environment file:

   ```bash
   cp .env.example .env
   ```

4. Generate app key:

   ```bash
   php artisan key:generate
   ```

5. Konfigurasi database di `.env`:

   ```env
   DB_DATABASE=nama_database
   DB_USERNAME=username
   DB_PASSWORD=password
   ```

6. Jalankan migrasi dan seeder:

   ```bash
   php artisan migrate --seed
   ```

7. Jalankan server:
   ```bash
   php artisan serve
   ```

## 📄 Lisensi

Proyek ini dibuat untuk keperluan **Uji Kompetensi Keahlian (UKK)**. Dilarang menyalahgunakan tanpa izin.

---

Dikembangkan oleh **Restu Prayuga**
