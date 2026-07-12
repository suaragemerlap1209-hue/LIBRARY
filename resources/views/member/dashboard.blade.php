@extends('layouts.member')

@section('title', 'Member Dashboard')

@section('content')

    {{-- ==================== STAT CARDS ==================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

        <div class="bg-white rounded-2xl border border-[#ECE7DC] p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wide text-gray-400 mb-2">BUKU DIPINJAM</p>
                <p class="text-3xl font-bold">{{ str_pad($stats['borrowed']['total'], 2, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    {{ $stats['borrowed']['note'] }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#F3F1E9] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1D3B2C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13M12 6.253C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s4.332.477 5.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#ECE7DC] p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wide text-gray-400 mb-2">TERLAMBAT</p>
                <p class="text-3xl font-bold">{{ str_pad($stats['late']['total'], 2, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $stats['late']['note'] }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#F3F1E9] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1D3B2C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#ECE7DC] p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wide text-gray-400 mb-2">TOTAL DENDA</p>
                <p class="text-3xl font-bold text-red-600">Rp {{ number_format($stats['fine_total'] / 1000, 0) }}k</p>
                <p class="text-xs text-red-400 mt-2 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Denda berjalan
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#F3F1E9] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1D3B2C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a4 4 0 00-8 0v2M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
    </div>

    {{-- ==================== BODY ==================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Riwayat Pinjam --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="flex items-center gap-2 font-semibold text-[15px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Riwayat Pinjam
                </h2>
                <a href="#" class="text-xs font-semibold text-gray-400 hover:text-gray-600 flex items-center gap-1">
                    LIHAT SEMUA
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="space-y-4">
                @foreach ($loans as $loan)
                    <div class="bg-white rounded-2xl border {{ $loan['status'] === 'terlambat' ? 'border-red-200 border-l-4 border-l-red-400' : 'border-[#ECE7DC]' }} p-4 flex items-center gap-4">

                        <img src="{{ $loan['cover'] }}" alt="{{ $loan['title'] }}" class="w-16 h-20 rounded-lg object-cover">

                        <div class="flex-1">
                            <p class="font-semibold">{{ $loan['title'] }}</p>
                            <p class="text-sm text-gray-400 italic mb-2">Oleh {{ $loan['author'] }}</p>

                            @if ($loan['status'] === 'selesai')
                                <p class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Kembali: {{ $loan['returned_at'] }}
                                </p>
                            @else
                                <div class="flex items-center gap-4 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Pinjam: {{ $loan['borrowed_at'] }}
                                    </span>
                                    <span class="flex items-center gap-1 {{ $loan['status'] === 'terlambat' ? 'text-red-500 font-medium' : '' }}">
                                        @if ($loan['status'] === 'terlambat')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 5a1 1 0 10-2 0v5a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L13 11.586V7z" clip-rule="evenodd"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @endif
                                        Tempo: {{ $loan['due_at'] }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <span @class([
                            'text-[11px] font-semibold px-2.5 py-1 rounded-md border',
                            'border-gray-300 text-gray-500' => $loan['status'] === 'aktif',
                            'border-red-300 text-red-500 bg-red-50' => $loan['status'] === 'terlambat',
                            'border-gray-200 text-gray-400 bg-gray-50' => $loan['status'] === 'selesai',
                        ])>
                            {{ strtoupper($loan['status']) }}
                        </span>

                        <button class="w-9 h-9 rounded-full border {{ $loan['status'] === 'terlambat' ? 'bg-red-500 border-red-500 text-white' : 'border-gray-200 text-gray-400' }} flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                @if ($loan['status'] === 'selesai')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                @elseif ($loan['status'] === 'terlambat')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a4 4 0 00-8 0v2M5 9h14l1 12H4L5 9z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                @endif
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-6">

            {{-- Tagihan Denda --}}
            <div class="bg-[#FDF4F2] border border-red-100 rounded-2xl p-5 relative overflow-hidden">
                <p class="flex items-center gap-2 text-sm font-semibold text-red-600 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    TAGIHAN DENDA
                </p>

                <div class="bg-white rounded-xl p-4 mb-4">
                    <p class="text-[11px] tracking-wide text-gray-400 font-medium mb-1">TOTAL BAYAR</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($fine['total'], 0, ',', '.') }}</p>
                </div>

                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-500">Denda Terlambat</span>
                    <span class="font-medium">Rp {{ number_format($fine['late_fee'], 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm mb-5">
                    <span class="text-gray-500">Admin Fee</span>
                    <span class="font-medium">Rp {{ number_format($fine['admin_fee'], 0, ',', '.') }}</span>
                </div>

                <button class="w-full bg-[#1D3B2C] text-white text-sm font-semibold py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-[#16301F] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Bayar Sekarang
                </button>
            </div>

            {{-- Rekomendasi --}}
            <div class="bg-white border border-[#ECE7DC] rounded-2xl p-5">
                <p class="text-sm font-semibold mb-4">Mungkin Kamu Suka</p>

                <div class="space-y-4 mb-4">
                    @foreach ($recommendations as $rec)
                        <div class="flex items-center gap-3">
                            <img src="{{ $rec['cover'] }}" alt="{{ $rec['title'] }}" class="w-12 h-14 rounded-lg object-cover">
                            <div>
                                <p class="text-sm font-semibold">{{ $rec['title'] }}</p>
                                <p class="text-xs text-gray-400">{{ $rec['category'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="#" class="block text-center border border-[#ECE7DC] rounded-xl py-2.5 text-xs font-semibold tracking-wide text-gray-600 hover:bg-gray-50">
                    JELAJAHI KATALOG
                </a>
            </div>
        </div>
    </div>

@endsection