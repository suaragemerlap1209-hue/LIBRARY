@extends('layouts.member')

@section('title', 'Payments')
@section('page-title', 'Account Status & History')

@section('content')

    @php
        $unpaidFines = $fines->where('status', '!=', 'paid');
        $historyFines = $fines->where('status', 'paid');
    @endphp

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-[#DCEBD9] text-[#2F5233] text-sm">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="mb-6 p-4 rounded-xl bg-amber-100 text-amber-800 text-sm">{{ session('info') }}</div>
    @endif

    {{-- ===== TOP SUMMARY ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-1 bg-[#152B1E] rounded-2xl p-8 flex flex-col justify-between min-h-[220px]">
            <p class="text-xs text-stone-300 uppercase tracking-wide">Total Outstanding</p>
            <p class="text-4xl font-semibold text-white my-4">Rp{{ number_format($totalFine, 0, ',', '.') }}</p>
            <p class="text-sm text-stone-300 leading-relaxed">
                Dihitung dari {{ $unpaidFines->count() }} denda belum dibayar.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6">
            <p class="text-xs text-stone-400 uppercase tracking-wide mb-2">Denda Belum Bayar</p>
            <h3 class="text-2xl font-semibold text-stone-900">{{ $unpaidFines->count() }}</h3>
            <p class="text-sm text-stone-500 mt-1">Menunggu pembayaran</p>
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6">
            <p class="text-xs text-stone-400 uppercase tracking-wide mb-2">Pembayaran Terakhir</p>
            @if ($historyFines->isNotEmpty())
                <h3 class="text-xl font-semibold text-stone-900">{{ $historyFines->first()->updated_at->format('d M Y') }}</h3>
            @else
                <h3 class="text-xl font-semibold text-stone-900">—</h3>
                <p class="text-sm text-stone-500 mt-1">Belum ada pembayaran</p>
            @endif
        </div>
    </div>

    {{-- ===== UNPAID FINES ===== --}}
    <div class="mb-4">
        <h3 class="text-xl font-semibold text-stone-900">Denda Belum Dibayar</h3>
    </div>

    @if ($unpaidFines->isEmpty())
        <div class="bg-white border border-stone-100 rounded-2xl p-8 text-center text-stone-500 mb-8">
            Tidak ada denda yang perlu dibayar. 🎉
        </div>
    @else
        <div class="flex flex-col gap-4 mb-8">
            @foreach ($unpaidFines as $fine)
                <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <a href="{{ route('member.payments.show', $fine) }}" class="font-semibold text-stone-900 hover:underline">
                            {{ $fine->loan->book->title ?? 'Buku tidak ditemukan' }}
                        </a>
                        <p class="text-sm text-stone-500">
                            Denda keterlambatan • Rp{{ number_format($fine->amount, 0, ',', '.') }}
                            @if ($fine->status === 'pending')
                                <span class="text-amber-600 font-medium">(menunggu verifikasi)</span>
                            @endif
                        </p>
                    </div>

                    {{-- Tombol bayar via Midtrans (logic API di controller dikerjakan tim lain) --}}
                    <a href="{{ route('member.payments.midtrans', $fine) }}"
                       class="bg-red-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-red-700 transition whitespace-nowrap text-center">
                        Bayar dengan Midtrans
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ===== PAYMENT HISTORY ===== --}}
    <div class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5">
            <h3 class="text-lg font-semibold text-stone-900">Payment History</h3>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-stone-50 border-y border-stone-100">
                <tr>
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Tanggal</th>
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Buku</th>
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Nominal</th>
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historyFines as $fine)
                    <tr class="border-b border-stone-50 last:border-0">
                        <td class="px-6 py-4 text-stone-700">{{ $fine->updated_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-stone-700">{{ $fine->loan->book->title ?? '-' }}</td>
                        <td class="px-6 py-4 text-stone-700">Rp{{ number_format($fine->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <x-badge-status status="available">Lunas</x-badge-status>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-stone-400">Belum ada riwayat pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection