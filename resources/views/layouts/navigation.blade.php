<aside class="w-64 bg-[#16331F] text-white flex flex-col fixed inset-y-0 left-0 z-30">

    {{-- Brand --}}
    <div class="px-6 py-6 border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="block">
            <div class="flex items-center gap-2 mb-0.5">
                <i class="fa-solid fa-feather-pointed text-[#E8B36A] text-lg"></i>
                <span class="text-white font-semibold text-lg tracking-tight">Lumina Library</span>
            </div>
            <p class="text-[10px] uppercase tracking-widest text-white/40 pl-6">Admin Portal</p>
        </a>
    </div>

    {{-- Nav Items --}}
    <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
                  {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-gauge w-4 text-center {{ request()->routeIs('dashboard') ? 'text-[#E8B36A]' : '' }}"></i>
            Dashboard
        </a>

        <a href="{{ Route::has('admin.books.index') ? route('admin.books.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
                  {{ request()->routeIs('admin.books.*') ? 'bg-white/10 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-book w-4 text-center {{ request()->routeIs('admin.books.*') ? 'text-[#E8B36A]' : '' }}"></i>
            Manajemen Buku
        </a>

        <a href="{{ Route::has('admin.members.index') ? route('admin.members.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
                  {{ request()->routeIs('admin.members.*') ? 'bg-white/10 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-users w-4 text-center {{ request()->routeIs('admin.members.*') ? 'text-[#E8B36A]' : '' }}"></i>
            Manajemen Anggota
        </a>

        <a href="{{ Route::has('admin.payments.index') ? route('admin.payments.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
                  {{ request()->routeIs('admin.payments.*') ? 'bg-white/10 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-file-invoice w-4 text-center {{ request()->routeIs('admin.payments.*') ? 'text-[#E8B36A]' : '' }}"></i>
            Verifikasi Pembayaran
        </a>

        <a href="{{ Route::has('admin.reports.index') ? route('admin.reports.index') : '#' }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.reports.*') ? 'bg-white/10 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <i class="fa-solid fa-chart-bar w-4 text-center {{ request()->routeIs('admin.reports.*') ? 'text-[#E8B36A]' : '' }}"></i>
                Laporan
         </a>

    </nav>

    {{-- Bottom --}}
    <div class="px-3 py-4 border-t border-white/10 space-y-0.5">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/60 hover:bg-white/10 hover:text-white transition">
            <i class="fa-solid fa-gear w-4 text-center"></i> Pengaturan
        </a>
        <a href="#"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/60 hover:bg-white/10 hover:text-white transition">
            <i class="fa-regular fa-circle-question w-4 text-center"></i> Bantuan
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-[#E8B36A] hover:bg-white/10 transition">
                <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Keluar
            </button>
        </form>
    </div>
</aside>