# Setup Tailwind CSS - Lokasi File

## 📁 File Konfigurasi Tailwind

### 1. **tailwind.config.js** (Root directory)
File konfigurasi utama Tailwind CSS
- Menentukan path content untuk scanning class Tailwind
- Mengatur theme dan plugins

### 2. **postcss.config.js** (Root directory)
Konfigurasi PostCSS untuk memproses Tailwind
- Plugin: tailwindcss, autoprefixer

### 3. **vite.config.js** (Root directory)
Konfigurasi Vite untuk build assets
- Input: `resources/css/app.css` dan `resources/js/app.js`

### 4. **resources/css/app.css**
File CSS utama dengan directive Tailwind:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### 5. **package.json**
Dependencies Tailwind:
- `tailwindcss`: ^3.4.1
- `@tailwindcss/forms`: ^0.5.7
- `autoprefixer`: ^10.4.17
- `postcss`: ^8.4.35
- `vite`: ^5.1.0

### 6. **resources/views/layouts/app.blade.php**
Layout utama yang memuat Tailwind via Vite:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

## 🚀 Cara Menggunakan

### Development (dengan hot reload):
```bash
npm run dev
```

### Production Build:
```bash
npm run build
```

## ✅ Status
- ✅ Dependencies sudah terinstall (`npm install` selesai)
- ✅ File konfigurasi lengkap
- ✅ Tailwind sudah terintegrasi dengan Vite
- ✅ Semua views menggunakan class Tailwind

## 📝 Catatan
Setelah menjalankan `npm run build`, file CSS yang sudah dikompilasi akan tersimpan di `public/build/` dan otomatis dimuat oleh Laravel Vite.





