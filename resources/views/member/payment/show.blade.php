@extends('layouts.member')

@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran')

@section('content')

    <a href="{{ route('member.payments.index') }}"
       class="inline-flex items-center gap-2 text-sm text-stone-500 hover:text-stone-800 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Payments
    </a>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-[#DCEBD9] text-[#2F5233] text-sm">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="mb-6 p-4 rounded-xl bg-amber-100 text-amber-800 text-sm">{{ session('info') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-8 max-w-2xl">
        <x-badge-status :status="$fine->status === 'paid' ? 'available' : 'pending'" class="mb-4">
            {{ match($fine->status) {
                'paid' => 'Lunas',
                'pending' => 'Menunggu Verifikasi',
                default => 'Belum Dibayar',
            } }}
        </x-badge-status>

        <h1 class="text-2xl font-semibold text-stone-900 mb-1">{{ $fine->loan->book->title ?? 'Buku tidak ditemukan' }}</h1>
        <p class="text-stone-500 mb-6">Denda keterlambatan pengembalian buku</p>

        <div class="grid grid-cols-2 gap-4 mb-8">
            <div>
                <p class="text-xs text-stone-400 uppercase tracking-wide">Nominal Denda</p>
                <p class="text-lg font-semibold text-stone-800">Rp{{ number_format($fine->amount, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs text-stone-400 uppercase tracking-wide">Jatuh Tempo Pinjaman</p>
                <p class="text-lg font-semibold text-stone-800">{{ $fine->loan->due_at->format('d M Y') }}</p>
            </div>
        </div>

        @if ($fine->status === 'unpaid')
            <a href="{{ route('member.payments.midtrans', $fine) }}"
               class="inline-block bg-red-600 text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-red-700 transition">
                Bayar dengan Midtrans
            </a>
        @elseif ($fine->status === 'pending')
            <p class="text-sm text-amber-700 bg-amber-50 rounded-lg px-4 py-3">
                Pembayaran sedang diverifikasi.
            </p>
        @else
            <p class="text-sm text-[#2F5233] bg-[#DCEBD9] rounded-lg px-4 py-3">
                Denda ini sudah lunas. Terima kasih! 🎉
            </p>
        @endif
    </div>

@endsection