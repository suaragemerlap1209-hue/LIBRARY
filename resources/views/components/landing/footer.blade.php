<footer class="bg-[#16331F] text-white px-8 lg:px-16 py-16">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

            <div>
                <div class="flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-feather-pointed text-[#E8B36A]"></i>
                    <span class="font-display font-medium">Lumina Library</span>
                </div>
                <p class="text-white/50 text-sm leading-relaxed">
                    Membangun masa depan melalui literasi —
                    satu buku, satu anggota, satu komunitas.
                </p>
            </div>

            {{-- Navigasi --}}
            <div>
                <p class="text-xs font-medium tracking-widest uppercase text-white/40 mb-4">Navigasi</p>
                <ul class="space-y-2 text-sm text-white/60">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                    </li>
                    <li>
                        <a href="{{ route('catalog.public') }}" class="hover:text-white transition">Koleksi Digital</a>
                    </li>
                    <li>
                        <a href="{{ route('bantuan') }}#panduan-pinjam" class="hover:text-white transition">Panduan Peminjaman</a>
                    </li>
                </ul>
            </div>

            {{-- Bantuan --}}
            <div>
                <p class="text-xs font-medium tracking-widest uppercase text-white/40 mb-4">Bantuan</p>
                <ul class="space-y-2 text-sm text-white/60">
                    <li>
                        <a href="{{ route('bantuan') }}#pusat-bantuan" class="hover:text-white transition">Pusat Bantuan</a>
                    </li>
                    <li>
                        <a href="{{ route('bantuan') }}#syarat-ketentuan" class="hover:text-white transition">Syarat & Ketentuan</a>
                    </li>
                    <li>
                        <a href="{{ route('bantuan') }}#kebijakan-denda" class="hover:text-white transition">Kebijakan Denda</a>
                    </li>
                    <li>
                        <a href="{{ route('bantuan') }}#hubungi" class="hover:text-white transition">Hubungi Kami</a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-white/30 text-xs">
                © {{ date('Y') }} Lumina Library. Dibangun untuk komunitas literasi.
            </p>
            <div class="flex items-center gap-2 text-white/30 text-xs">
                <i class="fa-solid fa-location-dot"></i>
                <span>BauBau, Sulawesi Tenggara</span>
            </div>
        </div>
    </div>
</footer>