@extends('layouts.member')

@section('title', 'Checkout Pembayaran Denda')
@section('page-title', 'Pembayaran Denda - ' . $fine->loan->book->title)

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- ===== HEADER ===== --}}
    <div class="mb-8">
        <a href="{{ route('member.payments.index') }}"
           class="text-sm text-[#8B5A2B] hover:text-[#6B3F1B] flex items-center gap-2 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Pembayaran
        </a>
        <h1 class="text-3xl font-bold text-stone-900 mb-2">Pembayaran Denda</h1>
        <p class="text-stone-600">Selesaikan pembayaran denda keterlambatan buku Anda</p>
    </div>

    {{-- ===== DETAIL DENDA ===== --}}
    <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6 mb-8">
        <div class="mb-6">
            <p class="text-xs text-stone-400 uppercase tracking-wide mb-2">Detail Denda</p>
            <h2 class="text-2xl font-bold text-stone-900">{{ $fine->loan->book->title }}</h2>
        </div>

        <div class="space-y-4 py-4 border-y border-stone-100">
            <div class="flex justify-between items-center">
                <p class="text-stone-600">Judul Buku</p>
                <p class="font-semibold text-stone-900">{{ $fine->loan->book->title }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-stone-600">Kategori</p>
                <p class="font-semibold text-stone-900">{{ $fine->loan->book->category->name ?? '-' }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-stone-600">Tanggal Pinjam</p>
                <p class="font-semibold text-stone-900">
                    {{ $fine->loan->borrowed_at?->translatedFormat('d F Y') ?? '-' }}
                </p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-stone-600">Jatuh Tempo</p>
                <p class="font-semibold text-red-600">
                    {{ $fine->loan->due_at?->translatedFormat('d F Y') ?? '-' }}
                </p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-stone-600">Hari Keterlambatan</p>
                <p class="font-semibold text-red-600">
                    {{ $fine->loan->due_at ? now()->diffInDays($fine->loan->due_at) : 0 }} hari
                </p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-stone-100">
            <div class="flex justify-between items-center">
                <p class="text-lg font-semibold text-stone-900">Nominal Denda</p>
                <p class="text-3xl font-bold text-red-600">
                    Rp{{ number_format($fine->amount, 0, ',', '.') }}
                </p>
            </div>
            <p class="text-xs text-stone-500 mt-2">
                Dihitung @ Rp1.000/hari
                × {{ $fine->loan->due_at ? now()->diffInDays($fine->loan->due_at) : 0 }} hari
            </p>
        </div>
    </div>

    {{-- ===== METODE PEMBAYARAN ===== --}}
    <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6 mb-8">
        <p class="text-xs text-stone-400 uppercase tracking-wide mb-4">Metode Pembayaran</p>
        <h3 class="text-lg font-semibold text-stone-900 mb-4">Pilih Cara Pembayaran</h3>

        <div class="space-y-3">
            {{-- ✅ FIX: Klik card trigger openSnap(), bukan auto-open --}}
            <div id="midtrans-card"
                 class="border-2 border-[#8B5A2B] bg-stone-50 rounded-xl p-5 cursor-pointer hover:bg-stone-100 transition"
                 onclick="openSnap()">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-[#8B5A2B] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white"
                             fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-stone-900">Midtrans Snap</p>
                        <p class="text-sm text-stone-600">Transfer Bank, E-wallet, QRIS, Cicilan, dll</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#8B5A2B]"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-blue-900">Aman & Terpercaya</p>
                    <p class="text-xs text-blue-800 mt-1">
                        Pembayaran diproses melalui Midtrans, gateway pembayaran terpercaya di Indonesia.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DISCLAIMER ===== --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8">
        <div class="flex gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v2m0 4v2m0 0a9 9 0 110-18 9 9 0 010 18z" />
            </svg>
            <div>
                <p class="text-sm font-medium text-amber-900">Penting!</p>
                <ul class="text-xs text-amber-800 mt-1 space-y-1">
                    <li>• Klik kartu di atas untuk membuka jendela pembayaran</li>
                    <li>• Jangan tutup halaman ini sampai proses pembayaran selesai</li>
                    <li>• Pembayaran akan diverifikasi secara otomatis oleh sistem</li>
                    <li>• Cek halaman pembayaran untuk melihat status terbaru</li>
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- ===== MIDTRANS SCRIPT ===== --}}
{{-- Gunakan sandbox untuk testing, ganti ke production saat deploy --}}
@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script>
    const snapToken = @json($snapToken);

    if (!snapToken) {
        console.error('Snap token tidak tersedia');
        // Disable card kalau token tidak ada
        document.getElementById('midtrans-card').innerHTML = `
            <div class="text-center text-red-600 text-sm py-2">
                ❌ Gagal memuat session pembayaran. 
                <a href="{{ route('member.payments.index') }}" class="underline">Kembali</a> dan coba lagi.
            </div>
        `;
    }

    // ✅ FIX: openSnap() dipanggil manual via onclick, bukan auto DOMContentLoaded
    function openSnap() {
        if (!snapToken) return;

        // Disable card saat popup terbuka supaya tidak double-click
        const card = document.getElementById('midtrans-card');
        card.onclick = null;
        card.classList.add('opacity-50', 'cursor-not-allowed');

        snap.pay(snapToken, {
            onSuccess: function(result) {
                console.log('Payment success:', result);
                // Langsung redirect — webhook sudah update DB
                window.location.href = '{{ route("member.payments.index") }}?status=success';
            },
            onPending: function(result) {
                console.log('Payment pending:', result);
                window.location.href = '{{ route("member.payments.index") }}?status=pending';
            },
            onError: function(result) {
                console.error('Payment error:', result);
                // Re-enable card supaya bisa coba lagi
                card.onclick = openSnap;
                card.classList.remove('opacity-50', 'cursor-not-allowed');
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                console.log('Popup ditutup');
                // Re-enable card supaya bisa buka lagi
                card.onclick = openSnap;
                card.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
</script>

@endsection