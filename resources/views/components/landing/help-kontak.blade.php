<section id="hubungi" class="scroll-mt-36">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-xl bg-[#16331F]/10 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-envelope text-[#16331F]"></i>
        </div>
        <div>
            <p class="text-[11px] text-[#9CA3AF] uppercase tracking-widest">Kontak</p>
            <h2 class="font-display text-2xl font-semibold text-[#1F2937]">Hubungi Kami</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        @foreach([
            ['icon' => 'fa-location-dot', 'judul' => 'Alamat',
             'val'  => 'Jl. Perpustakaan No. 1, BauBau, Sulawesi Tenggara 93711'],
            ['icon' => 'fa-clock',         'judul' => 'Jam Operasional',
             'val'  => "Senin – Jumat: 08.00 – 16.00 WITA\nSabtu: 08.00 – 12.00 WITA"],
            ['icon' => 'fa-envelope',      'judul' => 'Email',
             'val'  => 'perpus@luminalibrary.id'],
        ] as $kontak)
            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <div class="w-9 h-9 rounded-xl bg-[#16331F]/10 flex items-center justify-center mb-4">
                    <i class="fa-solid {{ $kontak['icon'] }} text-[#16331F] text-sm"></i>
                </div>
                <p class="text-xs font-semibold text-[#9CA3AF] uppercase tracking-wide mb-2">{{ $kontak['judul'] }}</p>
                <p class="text-sm text-[#374151] leading-relaxed whitespace-pre-line">{{ $kontak['val'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-[#16331F] rounded-2xl p-8 text-center">
        <p class="text-white font-semibold mb-2">Belum menemukan jawaban yang kamu cari?</p>
        <p class="text-white/60 text-sm mb-6">Tim kami siap membantu kamu di jam operasional perpustakaan.</p>
        <a href="mailto:perpus@luminalibrary.id"
           class="inline-flex items-center gap-2 bg-[#E8B36A] hover:bg-[#d9a25c] text-[#1F2937] font-semibold text-sm px-6 py-2.5 rounded-full transition">
            <i class="fa-solid fa-envelope"></i> Kirim Email
        </a>
    </div>
</section>
