<section class="py-20 px-6 lg:px-16 bg-[#16331F]">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="font-display text-3xl font-semibold text-white mb-4">Bergabunglah bersama kami</h2>
        <p class="text-white/60 text-sm leading-relaxed mb-8">
            Daftarkan diri kamu dan dapatkan akses ke ribuan koleksi buku fisik dan digital Lumina Library.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="inline-flex items-center justify-center gap-2 bg-[#E8B36A] hover:bg-[#d9a25c] text-[#1F2937] font-semibold text-sm px-7 py-3 rounded-full transition">
                <i class="fa-regular fa-user"></i> Daftar Sekarang
            </a>
            <a href="{{ route('catalog.public') }}"
               class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/15 text-white font-medium text-sm px-7 py-3 rounded-full transition border border-white/20">
                <i class="fa-solid fa-book-open"></i> Lihat Katalog
            </a>
        </div>
    </div>
</section>