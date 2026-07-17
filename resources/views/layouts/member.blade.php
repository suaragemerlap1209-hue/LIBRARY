<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lumina Library') | Lumina</title>

    @vite(['resources/css/app.css', 'resources/css/member.css', 'resources/js/app.js'])

    <style>
html,
body{
    margin:0;
    padding:0;
    height:100%;
}

body{
    overflow:hidden;
}

.layout{
    display:grid;
    grid-template-columns:256px 1fr;
    height:100vh;
}

.sidebar{
    position:sticky;
    top:0;
    height:100vh;
    overflow-y:auto;
    background:#fff;
    border-right:1px solid #ECE7DC;
}

.main{
    overflow-y:auto;
    height:100vh;
    background:#F7F5EF;
}

.topbar{
    position:sticky;
    top:0;
    background:#F7F5EF;
    z-index:50;
    border-bottom:1px solid #ECE7DC;
}
</style>
</head>

<body>

    {{-- ================= SIDEBAR ================= --}}
    <aside class="sidebar flex flex-col justify-between px-5 py-6">

        <div>

            <div class="layout">

                <div class="w-10 h-10 rounded-xl bg-[#F0EEE6] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5 text-[#1D3B2C]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 3c-4 0-7 3-7 7 0 5 4 9 7 11 3-2 7-6 7-11 0-4-3-7-7-7z"/>
                    </svg>
                </div>

                <div>
                    <h2 class="font-serif font-semibold text-lg">
                        Lumina Library
                    </h2>

                    <p class="text-xs text-stone-500 uppercase">
                        {{ Auth::user()->name }}
                    </p>
                </div>

            </div>

            @php
                $navItems = [
                    ['label'=>'Dashboard','route'=>'member.dashboard','icon'=>'grid'],
                    ['label'=>'Catalog','route'=>'member.catalog.index','icon'=>'book'],
                    ['label'=>'Loans','route'=>'member.loans.index','icon'=>'clipboard'],
                    ['label'=>'Payments','route'=>'member.payments.index','icon'=>'card'],
                ];
            @endphp

            <nav class="space-y-2">

                @foreach($navItems as $item)

                    @php
                        $active = request()->routeIs($item['route']);
                    @endphp

                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                       {{ $active ? 'bg-[#1D3B2C] text-white' : 'hover:bg-[#F5F5F5]' }}">

                        <x-member.nav-icon
                            :name="$item['icon']"
                            class="w-5 h-5"/>

                        {{ $item['label'] }}

                    </a>

                @endforeach

            </nav>

        </div>

        <div>

            <a href="{{ route('member.card') }}"
               class="block w-full text-center bg-[#1D3B2C] text-white rounded-xl py-3 font-semibold mb-4">
                Lihat Kartu Anggota
            </a>

            <div class="border-t pt-4 space-y-2">

                <a href="{{ route('member.settings') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#F3F1E9]">

                    <x-member.nav-icon
                        name="settings"
                        class="w-5 h-5"/>

                    Settings

                </a>

                <a href="{{ route('member.help') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#F3F1E9]">

                    <x-member.nav-icon
                        name="help"
                        class="w-5 h-5"/>

                    Help

                </a>

            </div>

        </div>

    </aside>

    {{-- ================= MAIN ================= --}}
    <div class="main">

        <header class="topbar flex items-center justify-between px-8 py-6">

            <h2 class="text-2xl font-semibold">
                @yield('page-title','Dashboard')
            </h2>

            <div class="flex items-center gap-4">

                @hasSection('topbar-search')
                    @yield('topbar-search')
                @endif

                <a href="{{ route('member.profile') }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-7 h-7 text-stone-500"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.5"
                              d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zm6 13.5a9 9 0 10-18 0"/>

                    </svg>

                </a>

            </div>

        </header>

        <main class="flex-1 px-8 py-8">
            @yield('content')
        </main>

    </div>

    @stack('scripts')

</body>
</html>