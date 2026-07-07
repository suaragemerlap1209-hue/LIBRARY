# 📚 Lumina Library

Sistem manajemen perpustakaan digital berbasis web, dikembangkan sebagai tugas kelompok mata kuliah **Pemrograman Web Lanjut** — Program Studi Informatika.

Lumina Library memudahkan pengelolaan koleksi buku, peminjaman, denda keterlambatan, dan pembayaran digital dalam satu platform terintegrasi.

---

## ✨ Fitur Utama

- **Manajemen Anggota** — Sistem role berbasis Admin dan Anggota (RBAC)
- **Katalog Buku** — Pencarian dan penelusuran koleksi buku secara publik maupun oleh anggota
- **Peminjaman & Pengembalian** — Pelacakan status pinjaman buku secara real-time
- **Perhitungan Denda Otomatis** — Denda keterlambatan Rp1.000/hari, dihitung otomatis via Laravel Scheduler
- **Pembayaran manual dan QRIS** — Pembayaran denda semi-otomatis dengan upload bukti transfer
- **Notifikasi Terjadwal** — Pengingat jatuh tempo pinjaman via email otomatis
- **Kartu Anggota Digital** — Kartu keanggotaan digital untuk setiap pengguna

---

## 🛠️ Tech Stack

| Kategori | Teknologi |
|---|---|
| Backend | Laravel 13 |
| Frontend | Blade + Tailwind CSS |
| Autentikasi | Laravel Breeze |
| Database | SQLite |
| Build Tool | Vite |
| Testing | Pest |

---

## 🚀 Instalasi & Setup

Pastikan sudah terinstal: **PHP ≥ 8.2**, **Composer**, **Node.js & npm**.

```bash
# 1. Clone repository
git clone https://github.com/suaragemerlap1209-hue/LIBRARY.git
cd LIBRARY

# 2. Install dependency PHP
composer install

# 3. Install dependency JavaScript
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Jalankan migrasi database (+ seeder jika tersedia)
php artisan migrate --seed

# 7. Build aset frontend
npm run build

# 8. Jalankan server lokal
php artisan serve
```

Aplikasi akan berjalan di `http://127.0.0.1:8000`.

> 💡 **Mode development:** gunakan `npm run dev` (bukan `npm run build`) agar perubahan CSS/JS langsung ter-refresh tanpa build ulang manual.

---

## 🌿 Alur Kolaborasi Tim

Proyek ini menggunakan strategi branching sederhana untuk menjaga stabilitas kode:

```
main        → versi stabil, siap didemokan/dinilai
  └── dev       → gabungan fitur yang sudah diuji
        ├── feature/nama-fitur-1
        ├── feature/nama-fitur-2
        └── feature/nama-fitur-3
```

**Aturan dasar:**
1. Jangan pernah commit langsung ke `main`
2. Buat branch baru dari `dev` untuk setiap fitur: `git checkout -b feature/nama-fitur`
3. Setelah selesai, ajukan **Pull Request** ke `dev` untuk direview
4. `main` hanya menerima merge dari `dev` yang sudah stabil

**Konvensi commit message:**
```
feat: menambahkan fitur perhitungan denda
fix: memperbaiki bug routing admin dashboard
docs: memperbarui instruksi instalasi
style: merapikan format Blade navbar
refactor: menyederhanakan logic pembayaran QRIS
```

---

## 👥 Kontributor

Dikembangkan oleh kelompok mahasiswa Informatika sebagai bagian dari tugas mata kuliah Pemrograman Web Lanjut.

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik.
