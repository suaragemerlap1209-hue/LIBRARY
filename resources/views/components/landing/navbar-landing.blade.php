<nav id="lp-nav" class="fixed top-0 left-0 right-0 z-50 px-8 lg:px-16 py-4 flex items-center justify-between bg-[#FAF7F0]/96 backdrop-blur border-b border-black/5">
    <a href="{{ route('home') }}" class="flex items-center gap-2">
        <i class="fa-solid fa-feather-pointed text-[#E8B36A] text-lg"></i>
        <span class="font-display font-medium text-[#16331F] text-lg tracking-tight">Lumina Library</span>
    </a>

    <ul class="hidden md:flex items-center gap-8">
        <li>
            <a href="{{ route('home') }}"
               class="nav-link text-sm text-[#1F2937] hover:text-[#16331F] transition">
                Beranda
            </a>
        </li>
        <li>
            <a href="{{ route('about') }}"
               class="nav-link text-sm text-[#1F2937] hover:text-[#16331F] transition">
                Tentang Kami
            </a>
        </li>
        <li>
            <a href="{{ route('catalog.public') }}"
               class="nav-link text-sm text-[#1F2937] hover:text-[#16331F] transition">
                Koleksi
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}#how"
               class="nav-link text-sm text-[#1F2937] hover:text-[#16331F] transition">
                Cara Bergabung
            </a>
        </li>
    </ul>

    <div class="flex items-center gap-3">
        <a href="{{ route('login') }}"
           class="text-sm font-medium text-[#16331F] hover:text-[#E8B36A] transition px-3 py-1.5">
            Masuk
        </a>
        <a href="{{ route('register') }}"
           class="text-sm font-medium bg-[#16331F] text-white px-5 py-2 rounded-full hover:bg-[#1F4429] transition">
            Mulai Bergabung
        </a>
    </div>
</nav>