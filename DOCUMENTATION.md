# Dokumentasi Aplikasi Peminjaman Alat

## Deskripsi
Aplikasi web untuk mengelola peminjaman alat dengan sistem role-based access control (Admin, Petugas, Peminjam).

## Teknologi
- **Backend**: Laravel 11
- **Frontend**: Tailwind CSS
- **Database**: MySQL

## Struktur Database (ERD)

### Tabel dan Relasi

1. **users**
   - Primary Key: id
   - Relasi: one-to-many dengan borrowings, one-to-many dengan activity_logs

2. **categories**
   - Primary Key: id
   - Relasi: one-to-many dengan tools

3. **tools**
   - Primary Key: id
   - Foreign Key: kategori_id → categories(id)
   - Relasi: many-to-one dengan categories, one-to-many dengan borrowing_details

4. **borrowings**
   - Primary Key: id
   - Foreign Key: user_id → users(id)
   - Relasi: many-to-one dengan users, one-to-many dengan borrowing_details, one-to-one dengan returns

5. **borrowing_details**
   - Primary Key: id
   - Foreign Key: borrowing_id → borrowings(id), tool_id → tools(id)
   - Relasi: many-to-one dengan borrowings, many-to-one dengan tools

6. **returns**
   - Primary Key: id
   - Foreign Key: borrowing_id → borrowings(id)
   - Relasi: many-to-one dengan borrowings

7. **activity_logs**
   - Primary Key: id
   - Foreign Key: user_id → users(id)
   - Relasi: many-to-one dengan users

## Flowchart

### 1. Flowchart Login
```
START
  ↓
Tampilkan Form Login
  ↓
Input Email & Password
  ↓
Validasi Credentials
  ↓
[Valid?]
  ├─ NO → Tampilkan Error → Kembali ke Form
  └─ YES → Cek Role User
         ├─ Admin → Redirect ke Dashboard Admin
         ├─ Petugas → Redirect ke Dashboard Petugas
         └─ Peminjam → Redirect ke Dashboard Peminjam
  ↓
END
```

### 2. Flowchart Peminjaman
```
START
  ↓
Peminjam Pilih Alat
  ↓
Isi Form Peminjaman
  ↓
Submit Peminjaman
  ↓
Cek Stok Alat
  ↓
[Stok Cukup?]
  ├─ NO → Tampilkan Error → Kembali ke Form
  └─ YES → Simpan Data Peminjaman
         ↓
         Status: Menunggu
         ↓
         Petugas Review
         ↓
         [Disetujui?]
           ├─ NO → Status: Ditolak → END
           └─ YES → Status: Disetujui
                  ↓
                  Kurangi Stok Alat (Trigger)
                  ↓
                  END
```

### 3. Flowchart Pengembalian
```
START
  ↓
Petugas Pilih Peminjaman
  ↓
Input Tanggal Kembali
  ↓
Hitung Keterlambatan
  ↓
[Hari Terlambat > 0?]
  ├─ YES → Hitung Denda (Function)
  └─ NO → Denda = 0
  ↓
Simpan Data Pengembalian
  ↓
Kembalikan Stok Alat (Trigger)
  ↓
Update Status: Dikembalikan
  ↓
END
```

## Modul Aplikasi

### Modul 1: Authentication
**Input**: Email, Password
**Proses**: 
- Validasi credentials
- Cek role user
- Generate session
- Log aktivitas
**Output**: Redirect ke dashboard sesuai role

### Modul 2: Manajemen User (Admin)
**Input**: Nama, Email, Password, Role
**Proses**: 
- Validasi input
- Hash password
- Simpan ke database
- Log aktivitas
**Output**: User baru tersimpan

### Modul 3: Manajemen Alat (Admin)
**Input**: Nama Alat, Kategori, Stok, Kondisi, Deskripsi
**Proses**: 
- Validasi input
- Simpan ke database
- Log aktivitas
**Output**: Alat baru tersimpan

### Modul 4: Peminjaman (Peminjam)
**Input**: Tanggal Pinjam, Daftar Alat & Jumlah
**Proses**: 
- Cek stok tersedia
- Simpan data peminjaman
- Simpan detail peminjaman
- Set status: Menunggu
- Log aktivitas
**Output**: Peminjaman berhasil diajukan

### Modul 5: Persetujuan Peminjaman (Petugas)
**Input**: ID Peminjaman, Aksi (Setujui/Tolak)
**Proses**: 
- Jika Setujui: Cek stok, kurangi stok, update status
- Jika Tolak: Update status, simpan keterangan
- Log aktivitas
**Output**: Status peminjaman terupdate

### Modul 6: Pengembalian (Petugas)
**Input**: ID Peminjaman, Tanggal Kembali, Keterangan
**Proses**: 
- Hitung denda (Function)
- Kembalikan stok alat (Trigger)
- Simpan data pengembalian
- Update status: Dikembalikan
- Log aktivitas
**Output**: Pengembalian berhasil diproses

### Modul 7: Laporan (Admin)
**Input**: Periode (Start Date, End Date)
**Proses**: 
- Query data berdasarkan periode
- Generate PDF
**Output**: File PDF laporan

## Database Functions & Procedures

### Function: hitung_denda
**Parameter**: tanggal_pinjam, tanggal_kembali, batas_hari
**Return**: DECIMAL(10,2)
**Deskripsi**: Menghitung denda berdasarkan keterlambatan (5000 per hari)

### Procedure: update_stok_setelah_peminjaman
**Parameter**: p_borrowing_id
**Deskripsi**: Mengurangi stok alat setelah peminjaman disetujui

### Procedure: update_stok_setelah_pengembalian
**Parameter**: p_borrowing_id
**Deskripsi**: Menambah stok alat setelah pengembalian

## Database Triggers

1. **log_aktivitas_borrowing_insert**: Log aktivitas saat peminjaman baru dibuat
2. **log_aktivitas_borrowing_update**: Log aktivitas saat status peminjaman berubah
3. **update_stok_after_borrowing_detail**: Update stok saat detail peminjaman dibuat (jika status disetujui)
4. **update_stok_after_return**: Update stok saat pengembalian dibuat

## Test Cases

1. **Test Login User**: Verifikasi user dapat login dengan credentials yang benar
2. **Test Tambah Alat**: Verifikasi admin dapat menambah alat baru
3. **Test Ajukan Peminjaman**: Verifikasi peminjam dapat mengajukan peminjaman
4. **Test Pengembalian + Denda**: Verifikasi sistem menghitung denda dengan benar
5. **Test Hak Akses User**: Verifikasi setiap role hanya dapat mengakses route yang diizinkan

## Instalasi

1. Clone repository
2. Install dependencies: `composer install && npm install`
3. Copy `.env.example` ke `.env`
4. Generate key: `php artisan key:generate`
5. Setup database di `.env`
6. Run migration: `php artisan migrate --seed`
7. Build assets: `npm run build`
8. Run server: `php artisan serve`

## Default Login

- **Admin**: admin@example.com / password
- **Petugas**: petugas@example.com / password
- **Peminjam**: peminjam@example.com / password

## Penjelasan Kode

### Model Relationships
- User → Borrowings (one-to-many)
- Category → Tools (one-to-many)
- Tool → BorrowingDetails (one-to-many)
- Borrowing → BorrowingDetails (one-to-many)
- Borrowing → Return (one-to-one)

### Middleware
- `CheckRole`: Memvalidasi role user sebelum mengakses route tertentu

### Controllers
- **AuthController**: Handle login/logout
- **DashboardController**: Menampilkan dashboard sesuai role
- **Admin Controllers**: CRUD untuk semua entitas
- **Petugas Controllers**: Approve/reject peminjaman, proses pengembalian
- **Peminjam Controllers**: Lihat alat, ajukan peminjaman

### Views
- Menggunakan Blade template dengan Tailwind CSS
- Responsive design untuk mobile dan desktop
- Komponen reusable (card, table, modal, alert)





