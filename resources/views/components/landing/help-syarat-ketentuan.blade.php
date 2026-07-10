<section id="syarat-ketentuan" class="scroll-mt-36">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-xl bg-[#E8B36A]/20 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-file-contract text-[#B9882F]"></i>
        </div>
        <div>
            <p class="text-[11px] text-[#9CA3AF] uppercase tracking-widest">Legal</p>
            <h2 class="font-display text-2xl font-semibold text-[#1F2937]">Syarat & Ketentuan</h2>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 divide-y divide-black/5">
        @foreach([
            ['judul' => '1. Keanggotaan',
             'isi'   => 'Pendaftaran akun Lumina Library terbuka untuk semua kalangan masyarakat. Setiap anggota wajib memberikan informasi yang benar dan lengkap saat registrasi. Akun yang terbukti menggunakan identitas palsu dapat dinonaktifkan tanpa pemberitahuan sebelumnya.'],
            ['judul' => '2. Peminjaman Buku',
             'isi'   => 'Setiap anggota dapat meminjam buku dengan jumlah berbeda berdasarkan katergori umur anggota terdaftar secara bersamaan. Masa peminjaman adalah 30 hari kalender terhitung sejak tanggal pinjam. Anggota bertanggung jawab penuh atas kondisi buku selama dalam masa peminjaman.'],
            ['judul' => '3. Pengembalian',
             'isi'   => 'Buku wajib dikembalikan sebelum atau tepat pada tanggal jatuh tempo. Pengembalian dilakukan secara langsung ke petugas perpustakaan atau via online dengan basis QR yang tertera. Buku yang dikembalikan dalam kondisi rusak dapat dikenakan biaya penggantian sesuai harga buku.'],
            ['judul' => '4. Denda Keterlambatan',
             'isi'   => 'Denda keterlambatan ditetapkan sebesar Rp1.000 per hari per buku, dihitung otomatis oleh sistem mulai H+1 setelah jatuh tempo. Denda wajib dilunasi sebelum anggota dapat meminjam buku kembali.'],
            ['judul' => '5. Kerahasiaan Data',
             'isi'   => 'Lumina Library berkomitmen menjaga kerahasiaan data pribadi anggota. Data yang dikumpulkan hanya digunakan untuk keperluan layanan perpustakaan dan tidak akan dibagikan kepada pihak ketiga tanpa persetujuan anggota.'],
            ['judul' => '6. Perubahan Ketentuan',
             'isi'   => 'Lumina Library berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diinformasikan melalui email terdaftar anggota. Penggunaan layanan setelah perubahan berlaku dianggap sebagai penerimaan atas ketentuan baru.'],
        ] as $item)
            <div class="px-6 py-5">
                <h3 class="font-semibold text-sm text-[#1F2937] mb-2">{{ $item['judul'] }}</h3>
                <p class="text-xs text-[#6B7280] leading-relaxed">{{ $item['isi'] }}</p>
            </div>
        @endforeach
    </div>
</section>
