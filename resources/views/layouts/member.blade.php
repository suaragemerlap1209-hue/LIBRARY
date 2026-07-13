<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lumina Library') | Lumina</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F7F5EF] text-stone-800">
    <div class="flex min-h-screen">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="w-64 shrink-0 bg-white border-r border-[#ECE7DC] flex flex-col justify-between px-5 py-6">
            <div>
                <div class="flex items-center gap-3 mb-8 px-1">
                    <div class="w-10 h-10 rounded-xl bg-[#F0EEE6] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1D3B2C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 0-7 3-7 7 0 5 4 9 7 11 3-2 7-6 7-11 0-4-3-7-7-7z"/>
                        </svg>
                    </div>
                        <div class="leading-tight">
                            <p class="font-serif font-semibold text-[16px] text-stone-900">Lumina</p>
                            <p class="text-[10px] tracking-widest text-stone-400 font-medium uppercase">{{ Auth::user()->name }}</p>
                        </div>                
                    </div>

                <nav class="flex flex-col gap-1">
                    @php
                        $navItems = [
                            ['label' => 'Dashboard', 'route' => 'member.dashboard', 'icon' => 'grid'],
                            ['label' => 'Catalog',   'route' => 'member.catalog.index', 'icon' => 'book'],
                            ['label' => 'Loans',     'route' => 'member.loans.index', 'icon' => 'clipboard'],
                            ['label' => 'Payments',  'route' => 'member.payments.index', 'icon' => 'card'],
                        ];
                    @endphp

                    @foreach ($navItems as $item)
                        @php
                            $isActive = request()->routeIs($item['route']);
                        @endphp
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition
                                  {{ $isActive
                                        ? 'bg-[#1D3B2C] text-white'
                                        : 'text-stone-600 hover:bg-[#F3F1E9]' }}">
                            <x-member.nav-icon :name="$item['icon']" class="w-5 h-5" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="space-y-1">
                <a href="{{ Route::has('member.card') ? route('member.card') : '#' }}"
                   class="flex items-center justify-center gap-2 w-full text-center bg-[#1D3B2C] text-white font-semibold text-sm
                          py-2.5 rounded-xl hover:bg-[#16301F] transition mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Lihat Kartu Anggota
                </a>

                <div class="flex flex-col gap-1 border-t border-[#ECE7DC] pt-4">
                    <a href="{{ Route::has('member.settings') ? route('member.settings') : '#' }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-stone-500 hover:bg-[#F3F1E9]">
                        <x-member.nav-icon name="settings" class="w-5 h-5" />
                        Settings
                    </a>
                    <a href="{{ Route::has('member.help') ? route('member.help') : '#' }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-stone-500 hover:bg-[#F3F1E9]">
                        <x-member.nav-icon name="help" class="w-5 h-5" />
                        Help
                    </a>
                </div>
            </div>
        </aside>

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="flex-1 flex flex-col">

            {{-- ================= TOPBAR ================= --}}
            <header class="flex items-center justify-between px-8 py-6 bg-[#F7F5EF]">
                <h2 class="text-2xl font-semibold text-stone-900">@yield('page-title', 'Dashboard')</h2>

                <div class="flex items-center gap-5">
                    @hasSection('topbar-search')
                        @yield('topbar-search')
                    @endif

                    <button class="text-stone-500 hover:text-stone-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                    </button>

                    <a href="{{ Route::has('member.profile') ? route('member.profile') : '#' }}"
                       class="text-stone-500 hover:text-stone-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </a>
                </div>
            </header>

            {{-- ================= PAGE CONTENT ================= --}}
            <main class="flex-1 px-8 pb-10">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>