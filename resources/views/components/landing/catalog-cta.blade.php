<section class="px-6 lg:px-16 pb-20">
    <div class="max-w-6xl mx-auto">
        <div class="bg-[#16331F] rounded-2xl p-8 text-center">
            <i class="fa-solid fa-lock text-[#E8B36A] text-2xl mb-4"></i>
            <h3 class="font-display text-xl font-semibold text-white mb-2">Ingin meminjam buku?</h3>
            <p class="text-white/60 text-sm mb-6">Daftar sebagai anggota Lumina Library dan mulai meminjam hingga 3 buku sekaligus.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 bg-[#E8B36A] hover:bg-[#d9a25c] text-[#1F2937] font-semibold text-sm px-6 py-2.5 rounded-full transition">
                    <i class="fa-regular fa-user"></i> Daftar Sekarang
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/15 text-white font-medium text-sm px-6 py-2.5 rounded-full transition border border-white/20">
                    <i class="fa-solid fa-right-to-bracket"></i> Sudah punya akun? Masuk
                </a>
            </div>
        </div>
    </div>
</section>