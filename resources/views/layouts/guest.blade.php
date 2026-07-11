<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lumina Library') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="antialiased bg-[#F4F1EB] text-[#1F2937]">
    <div class="min-h-screen px-6 lg:px-12 py-6">

        {{-- Top bar --}}
        <div class="flex items-center justify-between mb-10">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <i class="fa-solid fa-feather-pointed text-[#16331F] text-lg"></i>
                <span class="font-display text-lg font-semibold text-[#16331F]">Lumina Library</span>
            </a>
            <a href="{{ route('home') }}" class="text-sm text-[#6B7280] hover:text-[#16331F] transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>

        @if($centered ?? false)
            {{-- ================= MODE CENTERED (Login) ================= --}}
            <div class="max-w-md mx-auto text-center mb-8">
                <h1 class="font-display text-3xl font-semibold text-[#16331F] mb-2">Lumina Library</h1>
                <p class="text-sm text-[#9CA3AF]">Sistem Manajemen Perpustakaan</p>
            </div>

            <div class="max-w-md mx-auto bg-white rounded-2xl border border-black/5 shadow-sm p-8 mb-8">
                {{ $slot }}
            </div>

            <div class="max-w-md mx-auto text-center">
                <div class="flex items-center justify-center gap-3 text-xs text-[#9CA3AF] mb-2">
                    <a href="#" class="hover:text-[#16331F] transition">Panduan Sistem</a>
                    <span>&middot;</span>
                    <a href="#" class="hover:text-[#16331F] transition">Kebijakan Privasi</a>
                    <span>&middot;</span>
                    <a href="#" class="hover:text-[#16331F] transition">Bantuan</a>
                </div>
                <p class="text-xs text-[#9CA3AF]">&copy; {{ date('Y') }} Lumina Library. Suasana tenang untuk pikiran cemerlang.</p>
            </div>

        @else
            {{-- ================= MODE SPLIT (Register) ================= --}}
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

                {{-- Kolom kiri — showcase --}}
                <div class="pt-4">
                    <span class="text-xs font-semibold text-[#B9882F] tracking-widest uppercase">Lumina Library</span>
                    <h1 class="font-display text-4xl font-semibold text-[#1F2937] leading-tight mt-3 mb-4">
                        Masuki oase literasi yang tenang.
                    </h1>
                    <p class="text-sm text-[#6B7280] leading-relaxed mb-8 max-w-md">
                        Menjadi bagian dari komunitas pembaca kami dan nikmati ekosistem belajar yang berkelanjutan.
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-white rounded-2xl border border-black/5 p-5">
                            <div class="w-9 h-9 rounded-xl bg-[#16331F]/5 flex items-center justify-center mb-4">
                                <i class="fa-solid fa-book text-[#16331F] text-sm"></i>
                            </div>
                            <p class="text-xs text-[#9CA3AF] mb-1">Akses</p>
                            <p class="text-xl font-semibold text-[#1F2937] mb-1">50k+</p>
                            <p class="text-xs text-[#9CA3AF] mb-2">Koleksi</p>
                            <p class="text-xs text-[#6B7280]">Buku fisik dan digital dengan kurasi terbaik.</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-black/5 p-5">
                            <div class="w-9 h-9 rounded-xl bg-[#E8B36A]/15 flex items-center justify-center mb-4">
                                <i class="fa-solid fa-seedling text-[#B9882F] text-sm"></i>
                            </div>
                            <p class="text-xs text-[#9CA3AF] mb-1">Ruang</p>
                            <p class="text-base font-semibold text-[#1F2937] mb-2">Baca Tenang</p>
                            <p class="text-xs text-[#6B7280]">Interior organik yang meningkatkan fokus.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl overflow-hidden">
                        <img src="{{ asset('images/perpustakaan.jpg') }}"
                            alt="Interior Lumina Library"
                            class="w-full h-64 object-cover">
                    </div>
                </div>

                {{-- Kolom kanan — form --}}
                <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-8">
                    {{ $slot }}
                </div>

            </div>
        @endif
    </div>
</body>
</html>