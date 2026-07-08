<section id="hero" class="min-h-screen pt-20 lg:pt-0 flex items-center px-8 lg:px-16 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/3 left-1/4 w-[600px] h-[600px] rounded-full bg-[#EAF3DE]/40 blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-12 lg:gap-16 items-center py-20 relative z-10">
        <div>
            <span class="inline-flex items-center gap-2 text-xs font-medium tracking-widest uppercase text-[#16331F] bg-[#EAF3DE] px-3 py-1.5 rounded-full mb-6">
                <i class="fa-solid fa-circle-dot text-[8px]"></i>
                Ekosistem Literasi Berkualitas
            </span>

            <h1 class="font-display text-[2.8rem] lg:text-[4rem] leading-[1.1] text-[#1A1A18] mb-6">
                Gerbang Menuju<br>
                <em class="not-italic text-[#16331F]">Pengetahuan</em><br>
                Tak Terbatas
            </h1>

            <p class="text-[#6B7564] leading-relaxed text-[15px] mb-8 max-w-md">
                Temukan ketenangan dalam membaca di ekosistem literasi yang asri dan modern.
                Kami menyatukan koleksi fisik dan teknologi untuk pengalaman belajar yang lebih mudah dan bermakna.
            </p>

            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('catalog.public') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-full border-2 border-[#16331F] text-[#16331F] text-sm font-medium hover:bg-[#16331F] hover:text-white transition">
                    Jelajahi Koleksi <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#F0EBE0] text-[#1A1A18] text-sm font-medium hover:bg-[#F0EBE0]/70 transition">
                    Mulai Bergabung
                </a>
            </div>
        </div>

        <div class="relative">
            <div class="relative rounded-[2rem] overflow-hidden aspect-[4/5] max-w-md mx-auto shadow-2xl">
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=800&q=80"
                     alt="Perpustakaan Lumina" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#16331F]/30 to-transparent"></div>
            </div>

            <div class="float-card absolute -left-6 bottom-24 bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3 max-w-[220px]">
                <div class="w-10 h-10 rounded-xl bg-[#16331F] flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-fire text-[#E8B36A] text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] text-[#6B7564] uppercase tracking-wider">Populer hari ini</p>
                    <p class="text-[13px] font-medium text-[#1A1A18] leading-tight">Etika Alam Liar</p>
                </div>
            </div>

            <div class="absolute -right-4 top-16 bg-[#16331F] text-white rounded-2xl shadow-xl px-4 py-3 text-center">
                <p class="text-2xl font-display font-bold text-[#E8B36A]">98%</p>
                <p class="text-[10px] text-white/60 leading-tight">Tingkat Kepuasan<br>Anggota</p>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 opacity-40">
        <div class="w-px h-8 bg-[#1A1A18]"></div>
        <i class="fa-solid fa-chevron-down text-xs text-[#1A1A18]"></i>
    </div>
</section>