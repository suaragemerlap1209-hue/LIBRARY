<section id="panduan-pinjam" class="scroll-mt-36">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-xl bg-[#16331F]/10 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-book text-[#16331F]"></i>
        </div>
        <div>
            <p class="text-[11px] text-[#9CA3AF] uppercase tracking-widest">Panduan</p>
            <h2 class="font-display text-2xl font-semibold text-[#1F2937]">Panduan Peminjaman</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach([
            ['num' => '01', 'icon' => 'fa-user-plus',     'judul' => 'Daftar Akun',
             'desc' => 'Buat akun Lumina Library dengan nama lengkap dan email aktif. Verifikasi email untuk mengaktifkan akun.'],
            ['num' => '02', 'icon' => 'fa-magnifying-glass','judul' => 'Cari Buku',
             'desc' => 'Jelajahi katalog digital. Temukan buku berdasarkan judul, penulis, atau kategori yang tersedia.'],
            ['num' => '03', 'icon' => 'fa-file-lines',     'judul' => 'Ajukan Pinjaman',
             'desc' => 'Klik tombol Pinjam pada halaman detail buku. Sistem akan mencatat tanggal pinjam dan jatuh tempo otomatis.'],
        ] as $step)
            <div class="bg-white rounded-2xl border border-black/5 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#16331F]/8 flex items-center justify-center">
                        <i class="fa-solid {{ $step['icon'] }} text-[#16331F] text-sm"></i>
                    </div>
                    <span class="text-3xl font-display font-bold text-black/5">{{ $step['num'] }}</span>
                </div>
                <h3 class="font-semibold text-sm text-[#1F2937] mb-2">{{ $step['judul'] }}</h3>
                <p class="text-xs text-[#6B7280] leading-relaxed">{{ $step['desc'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach([
            ['num' => '04', 'icon' => 'fa-rotate-left',  'judul' => 'Kembalikan Tepat Waktu',
             'desc' => 'Batas peminjaman adalah 14 hari sejak tanggal pinjam. Kembalikan buku sebelum jatuh tempo untuk menghindari denda Rp1.000/hari/buku.'],
            ['num' => '05', 'icon' => 'fa-credit-card',  'judul' => 'Bayar Denda (jika ada)',
             'desc' => 'Jika terlambat, denda dihitung otomatis oleh sistem. Bayar via QRIS atau transfer bank, lalu upload bukti pembayaran di halaman Pembayaran Denda.'],
        ] as $step)
            <div class="bg-white rounded-2xl border border-black/5 p-6 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#16331F]/8 flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $step['icon'] }} text-[#16331F] text-sm"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold text-[#9CA3AF]">{{ $step['num'] }}</span>
                        <h3 class="font-semibold text-sm text-[#1F2937]">{{ $step['judul'] }}</h3>
                    </div>
                    <p class="text-xs text-[#6B7280] leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 bg-[#EAF3DE] border border-[#9CC07A]/30 rounded-xl px-5 py-4 flex items-start gap-3">
        <i class="fa-solid fa-circle-info text-[#3B6D11] mt-0.5"></i>
        <p class="text-sm text-[#3B6D11] leading-relaxed">
            Setiap anggota dapat meminjam berdasarkan <strong>umur dari anggota terdaftar</strong> secara bersamaan.
            Pastikan semua buku dikembalikan sebelum meminjam buku baru.
        </p>
    </div>
</section>
