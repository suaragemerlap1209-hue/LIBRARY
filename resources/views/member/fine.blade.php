@extends('layouts.member')

@section('title', 'Payments')
@section('page-title', 'Account Status & History')

@section('topbar-search')
    <form method="GET" action="{{ route('member.payments.index') }}" class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" placeholder="Search payments..."
               class="w-72 pl-9 pr-4 py-2 rounded-full bg-white border border-stone-200 text-sm
                      placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/30">
    </form>
@endsection

@section('content')

    @php
        $totalOutstanding = $unpaidFines->sum('amount');
        $lastPayment = $historyFines->firstWhere('status', 'paid');
    @endphp

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-[#DCEBD9] text-[#2F5233] text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-100 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===== TOP SUMMARY ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-1 bg-[#152B1E] rounded-2xl p-8 flex flex-col justify-between min-h-[220px]">
            <p class="text-xs text-stone-300 uppercase tracking-wide">Total Outstanding</p>
            <p class="text-4xl font-semibold text-white my-4">${{ number_format($totalOutstanding, 2) }}</p>
            <p class="text-sm text-stone-300 leading-relaxed">
                Calculated from {{ $unpaidFines->count() }} unpaid {{ Str::plural('fine', $unpaidFines->count()) }}.
                Please settle as soon as possible to avoid account suspension.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6">
            <p class="text-xs text-stone-400 uppercase tracking-wide mb-2">Unpaid Fines</p>
            <h3 class="text-2xl font-semibold text-stone-900">{{ $unpaidFines->count() }}</h3>
            <p class="text-sm text-stone-500 mt-1">Waiting for payment</p>
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6">
            <p class="text-xs text-stone-400 uppercase tracking-wide mb-2">Last Payment</p>
            @if ($lastPayment)
                <h3 class="text-xl font-semibold text-stone-900">{{ $lastPayment->updated_at->format('M d, Y') }}</h3>
                <p class="text-sm text-stone-500 mt-1">${{ number_format($lastPayment->amount, 2) }} for "{{ $lastPayment->loan->book->title }}"</p>
            @else
                <h3 class="text-xl font-semibold text-stone-900">—</h3>
                <p class="text-sm text-stone-500 mt-1">No payments yet</p>
            @endif
        </div>
    </div>

    {{-- ===== UNPAID FINES: UPLOAD BUKTI BAYAR ===== --}}
    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-xl font-semibold text-stone-900">Unpaid Fines</h3>
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
                        <p class="font-semibold text-stone-900">{{ $fine->loan->book->title ?? 'Buku tidak ditemukan' }}</p>
                        <p class="text-sm text-stone-500">Denda keterlambatan • ${{ number_format($fine->amount, 2) }}</p>
                    </div>

                    <form method="POST" action="{{ route('member.fines.pay', $fine) }}" enctype="multipart/form-data"
                          class="flex items-center gap-3">
                        @csrf
                        <label class="text-sm border border-stone-300 rounded-lg px-4 py-2 cursor-pointer hover:bg-stone-50 transition">
                            <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf" class="hidden" required
                                   onchange="this.closest('form').querySelector('span').textContent = this.files[0]?.name ?? 'Pilih file bukti bayar'">
                            <span>Pilih file bukti bayar</span>
                        </label>
                        <button type="submit"
                                class="bg-red-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-red-700 transition whitespace-nowrap">
                            Upload & Bayar
                        </button>
                    </form>
                </div>
                @error('proof')
                    <p class="text-sm text-red-600 -mt-3">{{ $message }}</p>
                @enderror
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
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Date</th>
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Book</th>
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Amount</th>
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Status</th>
                    <th class="text-left font-semibold text-stone-500 px-6 py-3">Receipt</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historyFines as $fine)
                    <tr class="border-b border-stone-50 last:border-0">
                        <td class="px-6 py-4 text-stone-700">{{ $fine->updated_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-stone-700">{{ $fine->loan->book->title ?? '-' }}</td>
                        <td class="px-6 py-4 text-stone-700">${{ number_format($fine->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <x-badge-status :status="$fine->status === 'paid' ? 'available' : 'pending'">
                                {{ $fine->status === 'paid' ? 'Approved' : 'Pending Review' }}
                            </x-badge-status>
                        </td>
                        <td class="px-6 py-4">
                            @if ($fine->receipt)
                                <a href="{{ asset('storage/' . $fine->receipt->file_path) }}" target="_blank"
                                   class="text-[#2F5233] font-medium hover:underline">
                                    Lihat file
                                </a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-stone-400">
                            Belum ada riwayat pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection