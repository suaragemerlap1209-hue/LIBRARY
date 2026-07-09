<section id="cara-bergabung" class="py-24 px-8 lg:px-16 bg-white reveal">
    <div class="max-w-4xl mx-auto text-center">
        <p class="text-xs font-medium tracking-widest uppercase text-[#6B7564] mb-2">Cara Bergabung</p>
        <h2 class="font-display text-3xl text-[#1A1A18] mb-3">Tiga Langkah Mudah</h2>
        <p class="text-[#6B7564] text-sm mb-16">Mulai perjalanan literasi Anda hari ini.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <div class="hidden md:block absolute top-8 left-1/4 right-1/4 h-px bg-[#EAF3DE]"></div>

            @php
                $steps = [
                    ['num' => '1', 'icon' => 'fa-magnifying-glass', 'title' => 'Cari Buku',
                     'sub' => '"Mulai dari Pencarian"',
                     'desc' => 'Cari judul favorit Anda melalui katalog digital. Temukan antara koleksi fisik dan riwayat buku yang tersedia.'],
                    ['num' => '2', 'icon' => 'fa-book',             'title' => 'Pinjam',
                     'sub' => '"Verifikasi Digital"',
                     'desc' => 'Ajukan peminjaman secara online. Sistem mencatat tanggal peminjaman dan batas pengembalian secara otomatis.'],
                    ['num' => '3', 'icon' => 'fa-rotate-left',      'title' => 'Kembalikan',
                     'sub' => '"Tepat Waktu"',
                     'desc' => 'Kembalikan buku sebelum jatuh tempo. Sistem akan menghitung denda otomatis jika terlambat — Rp1.000/hari/buku.'],
                ];
            @endphp

            @foreach($steps as $step)
                <div class="flex flex-col items-center">
                    <div class="relative w-16 h-16 rounded-full border-2 border-[#EAF3DE] bg-white flex items-center justify-center mb-5 z-10">
                        <i class="fa-solid {{ $step['icon'] }} text-[#16331F] text-lg"></i>
                    </div>
                    <p class="text-[10px] text-[#6B7564] tracking-wider uppercase mb-1">{{ $step['num'] . '. ' . $step['title'] }}</p>
                    <p class="text-xs text-[#C9973A] mb-3 italic">{{ $step['sub'] }}</p>
                    <p class="text-[#6B7564] text-sm leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-12 inline-flex items-center gap-3 bg-[#FAF7F0] border border-[#F0EBE0] rounded-full px-6 py-3 text-sm text-[#6B7564]">
            <i class="fa-regular fa-circle-exclamation text-[#C9973A]"></i>
            Pastikan mengembalikan buku sebelum batas waktu untuk menghindari denda administratif.
        </div>
    </div>
</section>