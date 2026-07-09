<section class="py-24 px-8 lg:px-16 reveal">
    <div class="max-w-7xl mx-auto">
        <div class="bg-[#16331F] rounded-3xl px-10 py-16 text-center">
            <p class="text-xs font-medium tracking-widest uppercase text-[#E8B36A]/60 mb-3">Keistimewaan Anggota</p>
            <h2 class="font-display text-3xl text-white mb-3">Lebih dari Sekadar Kartu Perpustakaan</h2>
            <p class="text-white/50 text-sm max-w-md mx-auto mb-14">
                Menjadi bagian dari komunitas Lumina Library memberi Anda akses eksklusif ke fasilitas dan sistem yang dirancang untuk kenyamanan Anda.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $features = [
                        ['icon' => 'fa-book-open-reader', 'title' => '5.000+ Koleksi',
                         'desc' => 'Akses tanpa batas ke ribuan judul koleksi fisik berkualitas tinggi.'],
                        ['icon' => 'fa-id-card',           'title' => 'Kartu Anggota Digital',
                         'desc' => 'Kelola profil, status, dan riwayat peminjaman langsung dari aplikasi.'],
                        ['icon' => 'fa-bolt',              'title' => 'Sistem Pinjam Instan',
                         'desc' => 'Proses peminjaman dan pengembalian yang cepat, transparan, dan tercatat otomatis.'],
                    ];
                @endphp
                @foreach($features as $feat)
                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 rounded-2xl bg-[#C9973A]/20 border border-[#C9973A]/30 flex items-center justify-center mb-4">
                            <i class="fa-solid {{ $feat['icon'] }} text-[#E8B36A] text-lg"></i>
                        </div>
                        <h3 class="text-white font-medium mb-2">{{ $feat['title'] }}</h3>
                        <p class="text-white/50 text-sm leading-relaxed">{{ $feat['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>