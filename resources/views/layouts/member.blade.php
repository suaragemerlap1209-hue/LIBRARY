<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Member Dashboard') - Aethelgard Botanical Library</title>
    @vite(['resources/css/member.css'])
</head>
<body class="bg-[#F7F4EC] text-[#1A1A1A] font-sans antialiased">

    <div class="flex min-h-screen">

        {{-- ==================== SIDEBAR ==================== --}}
        <aside class="w-64 shrink-0 bg-white border-r border-[#ECE7DC] flex flex-col justify-between px-5 py-6">
            <div>
                <div class="flex items-center gap-3 mb-10 px-1">
                    <div class="w-10 h-10 rounded-xl bg-[#F0EEE6] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1D3B2C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4 0-7 3-7 7 0 5 4 9 7 11 3-2 7-6 7-11 0-4-3-7-7-7z"/>
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <p class="font-semibold text-[15px]">Aethelgard</p>
                        <p class="text-[10px] tracking-widest text-gray-400 font-medium">BOTANICAL LIBRARY</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <x-member-nav-item href="{{ route('member.dashboard') }}" :active="request()->routeIs('member.dashboard')" label="Dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                    </x-member-nav-item>

                    <x-member-nav-item href="#" label="Catalog">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13M12 6.253C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s4.332.477 5.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </x-member-nav-item>

                    <x-member-nav-item href="#" label="Loans">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </x-member-nav-item>

                    <x-member-nav-item href="#" label="Fines">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a4 4 0 00-8 0v2M5 9h14l1 12H4L5 9z"/></svg>
                    </x-member-nav-item>
                </nav>
            </div>

            {{-- Quick Scan dihilangkan sesuai permintaan --}}
            <div class="space-y-1 border-t border-[#ECE7DC] pt-4">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-500 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Help Center
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-500 hover:bg-red-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </a>
            </div>
        </aside>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <div class="flex-1 flex flex-col">

            <header class="flex items-center justify-between px-10 py-6">
                <div>
                    <h1 class="text-xl font-semibold">Selamat Datang, {{ explode(' ', $member['name'])[0] }}.</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $today }} • ID Anggota:
                        <span class="ml-1 px-2 py-0.5 bg-[#EFEDE4] rounded text-xs font-medium text-gray-700">{{ $member['member_id'] }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <button class="relative w-10 h-10 rounded-full bg-white border border-[#ECE7DC] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1.5 right-2 w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    </button>

                    <div class="w-px h-8 bg-[#ECE7DC]"></div>

                    <div class="flex items-center gap-3">
                        <img src="{{ $member['avatar'] }}" alt="{{ $member['name'] }}" class="w-10 h-10 rounded-full object-cover">
                        <div class="leading-tight">
                            <p class="text-sm font-semibold">{{ $member['name'] }}</p>
                            <p class="text-xs text-gray-400">{{ $member['role'] }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="px-10 pb-10 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>