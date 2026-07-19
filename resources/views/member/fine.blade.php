@extends('layouts.member')

@section('title', 'Pembayaran')
@section('page-title', 'Status Akun & Riwayat')

@section('topbar-search')
    <form method="GET" action="{{ route('member.payments.index') }}" class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" placeholder="Cari pembayaran..."
               class="w-72 pl-9 pr-4 py-2 rounded-full bg-white border border-stone-200 text-sm
                      placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/30">
    </form>
@endsection

@section('content')

    @php
        $totalOutstanding = $unpaidFines->sum('amount');
        $lastPayment = $historyFines->last();
    @endphp

    {{-- ===== SUCCESS/ERROR MESSAGES ===== --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-[#DCEBD9] text-[#2F5233] text-sm font-medium">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-100 text-red-700 text-sm font-medium">
            ✗ {{ session('error') }}
        </div>
    @endif

    {{-- ===== RINGKASAN STATISTIK ATAS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Card 1: Total Tunggakan --}}
        <div class="lg:col-span-1 bg-[#152B1E] rounded-2xl p-8 flex flex-col justify-between min-h-[220px]">
            <p class="text-xs text-stone-300 uppercase tracking-wide">Total Tunggakan</p>
            <p class="text-4xl font-bold text-white my-4">Rp{{ number_format($totalOutstanding, 0, ',', '.') }}</p>
            <p class="text-sm text-stone-300 leading-relaxed">
                Dihitung dari <strong>{{ $unpaidFines->count() }} denda</strong> yang belum dibayar.
                Segera lunasi untuk menghindari penangguhan akun.
            </p>
        </div>

        {{-- Card 2: Jumlah Denda Belum Dibayar --}}
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6">
            <p class="text-xs text-stone-400 uppercase tracking-wide mb-2">Denda Belum Dibayar</p>
            <h3 class="text-3xl font-bold text-stone-900">{{ $unpaidFines->count() }}</h3>
            <p class="text-sm text-stone-500 mt-2">Menunggu pembayaran Anda</p>
        </div>

        {{-- Card 3: Pembayaran Terakhir --}}
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6">
            <p class="text-xs text-stone-400 uppercase tracking-wide mb-2">Pembayaran Terakhir</p>
            @if ($lastPayment)
                <h3 class="text-lg font-semibold text-stone-900">{{ $lastPayment->paid_at?->translatedFormat('d F Y') ?? '-' }}</h3>
                <p class="text-sm text-stone-600 mt-2">
                    Rp{{ number_format($lastPayment->amount, 0, ',', '.') }}
                    <br>
                    <span class="text-xs text-stone-500">{{ $lastPayment->loan->book->title ?? 'N/A' }}</span>
                </p>
            @else
                <h3 class="text-lg font-semibold text-stone-900">—</h3>
                <p class="text-sm text-stone-500 mt-2">Belum ada pembayaran</p>
            @endif
        </div>
    </div>

    {{-- ===== DENDA BELUM DIBAYAR ===== --}}
    <div class="mb-4">
        <h3 class="text-xl font-semibold text-stone-900">Denda Belum Dibayar</h3>
    </div>

    @if ($unpaidFines->isEmpty())
        <div class="bg-white border border-stone-100 rounded-2xl p-8 text-center text-stone-500 mb-8">
            <p class="text-lg">Tidak ada denda yang perlu dibayar. 🎉</p>
            <p class="text-sm mt-2">Akun Anda bersih! Terus jaga tanggung jawab peminjaman.</p>
        </div>
    @else
        <div class="flex flex-col gap-4 mb-8">
            @foreach ($unpaidFines as $fine)
                <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:shadow-md transition">
                    {{-- Icon Badge --}}
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z" />
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1">
                        <p class="font-semibold text-stone-900 text-lg">{{ $fine->loan->book->title ?? 'Buku tidak ditemukan' }}</p>
                        <p class="text-sm text-stone-500 mt-1">
                            Denda keterlambatan •
                            <span class="font-semibold text-red-600">Rp{{ number_format($fine->amount, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    {{-- Action Button --}}
                    @if ($fine->status === 'processing')
                        {{-- Sedang diproses Midtrans — bisa lanjut bayar kalau popup tertutup --}}
                        <a href="{{ route('member.fines.pay', $fine) }}"
                        class="bg-blue-600 text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-blue-700 active:scale-95 transition whitespace-nowrap shadow-sm">
                            Lanjutkan Pembayaran
                        </a>
                    @elseif ($fine->status === 'pending')
                        {{-- Pending konfirmasi bank/e-wallet --}}
                        <span class="bg-amber-100 text-amber-700 text-sm font-semibold px-6 py-3 rounded-lg whitespace-nowrap">
                            ⏳ Menunggu Konfirmasi
                        </span>
                    @else
                        {{-- Unpaid — bayar via Midtrans --}}
                        <a href="{{ route('member.fines.pay', $fine) }}"
                        class="bg-red-600 text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-red-700 active:scale-95 transition whitespace-nowrap shadow-sm">
                            Bayar via Midtrans
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- ===== RIWAYAT PEMBAYARAN ===== --}}
    <div class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-stone-100">
            <h3 class="text-lg font-semibold text-stone-900">Riwayat Pembayaran</h3>
        </div>

        @if ($historyFines->isEmpty())
            <div class="px-6 py-10 text-center text-stone-400">
                <p>Belum ada riwayat pembayaran.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead class="bg-stone-50 border-b border-stone-100">
                    <tr>
                        <th class="text-left font-semibold text-stone-600 px-6 py-3">Tanggal Bayar</th>
                        <th class="text-left font-semibold text-stone-600 px-6 py-3">Buku</th>
                        <th class="text-left font-semibold text-stone-600 px-6 py-3">Jumlah</th>
                        <th class="text-left font-semibold text-stone-600 px-6 py-3">Metode</th>
                        <th class="text-left font-semibold text-stone-600 px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historyFines as $fine)
                        <tr class="border-b border-stone-50 last:border-0 hover:bg-stone-50">
                            <td class="px-6 py-4 text-stone-700">
                                {{ $fine->paid_at?->translatedFormat('d F Y') ?? $fine->updated_at->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-stone-700 max-w-xs truncate">
                                {{ $fine->loan->book->title ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-stone-900 font-semibold">
                                Rp{{ number_format($fine->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-stone-600 capitalize">
                                {{ $fine->payment_type ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{-- ✅ FIXED: Check untuk status 'paid' bukan 'success' --}}
                                @if ($fine->status === 'paid')
                                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full inline-block">
                                        ✓ Lunas
                                    </span>
                                @else
                                    {{-- Fallback untuk status lain (tidak seharusnya ada di history, tapi safety check) --}}
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1.5 rounded-full inline-block capitalize">
                                        {{ $fine->status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection