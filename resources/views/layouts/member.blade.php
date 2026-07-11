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
        <aside class="w-64 shrink-0 bg-[#152B1E] text-stone-100 flex flex-col justify-between">
            <div>
                <div class="px-6 pt-8 pb-6">
                    <h1 class="text-2xl font-serif tracking-wide text-[#E8DCC4]">Lumina</h1>
                    <p class="text-xs text-stone-400 mt-1">Library Member</p>
                </div>

                <nav class="mt-4 flex flex-col gap-1 px-3">
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
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                                  {{ $isActive
                                        ? 'bg-[#8B5A2B] text-white'
                                        : 'text-stone-300 hover:bg-white/5 hover:text-white' }}">
                            <x-member.nav-icon :name="$item['icon']" class="w-5 h-5" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="px-4 pb-6">
                <a href="{{ Route::has('member.rooms.reserve') ? route('member.rooms.reserve') : '#' }}"
                   class="block w-full text-center bg-[#F3C89C] text-[#152B1E] font-semibold text-sm
                          py-2.5 rounded-lg hover:bg-[#efb87e] transition mb-4">
                    Reserve a Room
                </a>

                <div class="flex flex-col gap-1 border-t border-white/10 pt-4">
                    <a href="{{ Route::has('member.settings') ? route('member.settings') : '#' }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-stone-300 hover:bg-white/5 hover:text-white">
                        <x-member.nav-icon name="settings" class="w-5 h-5" />
                        Settings
                    </a>
                    <a href="{{ Route::has('member.help') ? route('member.help') : '#' }}"
                       class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-stone-300 hover:bg-white/5 hover:text-white">
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