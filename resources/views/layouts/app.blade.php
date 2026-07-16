<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lumina Library') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="icon" type="image/x-icon" href="{{ asset('images/leaf.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="font-sans antialiased bg-[#F4F1EB] text-[#1F2937]">
    <div class="min-h-screen">

        {{-- Sidebar (fixed di kiri) --}}
        @include('layouts.navigation')

        {{-- Area konten utama, digeser ke kanan sejauh lebar sidebar (w-64) --}}
        <div class="ml-64 min-h-screen flex flex-col">

            {{-- Topbar --}}
            <header class="sticky top-0 z-20 bg-[#F4F1EB]/95 backdrop-blur border-b border-black/5 px-8 py-5 flex items-start justify-between gap-4">
                <div>
                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:flex items-center gap-2 bg-white border border-black/5 rounded-full px-4 py-2 text-sm text-[#9CA3AF]">
                        <i class="fa-regular fa-calendar text-xs"></i>
                        {{ now()->translatedFormat('l, d M Y') }}
                    </div>

                    <button class="relative w-10 h-10 rounded-full bg-white border border-black/5 flex items-center justify-center hover:bg-black/[0.02] transition">
                        <i class="fa-regular fa-bell text-[#6B7280]"></i>
                        <span class="absolute top-2 right-2.5 w-1.5 h-1.5 rounded-full bg-[#E8B36A]"></span>
                    </button>

                    <div class="w-10 h-10 rounded-full bg-[#16331F] text-white flex items-center justify-center text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mx-8 mt-4 bg-[#EAF3DE] border border-[#3B6D11]/20 text-[#3B6D11] px-4 py-3 rounded-xl text-sm">
                    <i class="fa-solid fa-circle-check mr-1.5"></i>{{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mx-8 mt-4 bg-[#FCEBEB] border border-[#A32D2D]/20 text-[#A32D2D] px-4 py-3 rounded-xl text-sm">
                    <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 px-8 py-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>