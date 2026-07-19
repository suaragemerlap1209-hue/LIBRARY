{{-- resources/views/admin/fines/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-widest mb-0.5">Manajemen</p>
            <h2 class="text-xl font-bold text-[#1F2937]">Denda & Pembayaran</h2>
        </div>
    </x-slot>

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-5">
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide mb-1">Belum Dibayar</p>
            <p class="text-2xl font-bold text-[#1F2937]">{{ $stats['total_unpaid'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-5">
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide mb-1">Sedang Diproses</p>
            <p class="text-2xl font-bold text-[#B9882F]">{{ $stats['total_pending'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-5">
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide mb-1">Sudah Lunas</p>
            <p class="text-2xl font-bold text-[#3B6D11]">{{ $stats['total_paid'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-5">
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide mb-1">Total Terkumpul</p>
            <p class="text-2xl font-bold text-[#16331F]">Rp{{ number_format($stats['amount_collected'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-[#EAF3DE] text-[#3B6D11] text-sm font-medium">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-[#FCEBEB] text-[#A32D2D] text-sm font-medium">
            ✗ {{ session('error') }}
        </div>
    @endif

    {{-- ===== TABLE ===== --}}
    <x-table-wrapper :is-empty="$fines->isEmpty()" empty-text="Tidak ada denda ditemukan.">

        <x-slot name="filters">
            <form method="GET" class="flex gap-2 flex-wrap">
                {{-- Search --}}
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#9CA3AF]"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari anggota atau buku..."
                           class="pl-9 pr-4 py-2 rounded-xl border border-black/10 text-sm text-[#374151] focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F] w-64">
                </div>

                {{-- Filter Status --}}
                <select name="status" class="rounded-xl border border-black/10 text-sm text-[#374151] px-3 py-2 focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                    <option value="">Semua Status</option>
                    <option value="unpaid"     @selected(request('status') === 'unpaid')>Belum Dibayar</option>
                    <option value="pending"    @selected(request('status') === 'pending')>Pending</option>
                    <option value="processing" @selected(request('status') === 'processing')>Processing (Midtrans)</option>
                    <option value="paid"       @selected(request('status') === 'paid')>Lunas</option>
                </select>

                <button type="submit"
                        class="bg-black/5 text-[#374151] text-sm font-medium px-4 py-2 rounded-xl hover:bg-black/10 transition">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.fines.index') }}"
                       class="text-sm text-[#9CA3AF] px-3 py-2 hover:text-[#374151] transition">
                        Reset
                    </a>
                @endif
            </form>
        </x-slot>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-black/5">
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Anggota</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Buku</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Jatuh Tempo</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Nominal</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Metode</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Status</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @foreach ($fines as $fine)
                    <tr class="hover:bg-black/[0.01] transition">

                        {{-- Anggota --}}
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#16331F]/10 text-[#16331F] flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($fine->loan->user->name ?? '-', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-[#1F2937]">{{ $fine->loan->user->name ?? '-' }}</p>
                                    <p class="text-xs text-[#9CA3AF]">{{ $fine->loan->user->member_id ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Buku --}}
                        <td class="py-4 text-[#6B7280] max-w-[180px] truncate">
                            {{ $fine->loan->book->title ?? '-' }}
                        </td>

                        {{-- Jatuh Tempo --}}
                        <td class="py-4 text-[#6B7280]">
                            {{ $fine->loan->due_at?->format('d M Y') ?? '-' }}
                        </td>

                        {{-- Nominal --}}
                        <td class="py-4 font-semibold text-[#1F2937]">
                            Rp{{ number_format($fine->amount, 0, ',', '.') }}
                        </td>

                        {{-- Metode --}}
                        <td class="py-4 text-[#6B7280] capitalize">
                            @if ($fine->payment_type === 'midtrans')
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 inline-block"></span>
                                    Midtrans
                                </span>
                            @elseif ($fine->payment_type === 'cash')
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                    Tunai
                                </span>
                            @else
                                <span class="text-[#9CA3AF]">—</span>
                            @endif
                        </td>

                        {{-- Status Badge --}}
                        <td class="py-4">
                            @switch($fine->status)
                                @case('unpaid')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600">
                                        Belum Dibayar
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FDF3E3] text-[#B9882F]">
                                        Pending
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600">
                                        Processing
                                    </span>
                                    @break
                                @case('paid')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#EAF3DE] text-[#3B6D11]">
                                        ✓ Lunas
                                    </span>
                                    @break
                            @endswitch
                        </td>

                        {{-- Aksi --}}
                        <td class="py-4 text-right">
                            @if (in_array($fine->status, ['unpaid', 'pending']))
                                {{-- Tombol Tandai Lunas (tunai) --}}
                                <button type="button"
                                        x-data="{}"
                                        x-on:click="$dispatch('open-modal', 'confirm-paid-{{ $fine->id }}')"
                                        class="text-[#3B6D11] font-semibold hover:underline text-xs">
                                    Tandai Lunas
                                </button>
                            @elseif ($fine->status === 'processing')
                                <span class="text-xs text-blue-500 italic">Menunggu Midtrans</span>
                            @else
                                {{-- Lunas: tampilkan tanggal bayar --}}
                                <span class="text-xs text-[#9CA3AF]">
                                    {{ $fine->paid_at?->format('d M Y') ?? '-' }}
                                </span>
                            @endif
                        </td>
                    </tr>

                    {{-- Modal Konfirmasi Tandai Lunas --}}
                    @if (in_array($fine->status, ['unpaid', 'pending']))
                        <x-modal name="confirm-paid-{{ $fine->id }}" :show="false" max-width="sm">
                            <div class="p-6">
                                <h3 class="text-sm font-semibold text-[#1F2937] mb-2">Tandai Lunas (Tunai)?</h3>
                                <p class="text-xs text-[#6B7280] mb-1">
                                    Anggota: <strong>{{ $fine->loan->user->name ?? '-' }}</strong>
                                </p>
                                <p class="text-xs text-[#6B7280] mb-1">
                                    Buku: <strong>{{ $fine->loan->book->title ?? '-' }}</strong>
                                </p>
                                <p class="text-xs text-[#6B7280] mb-5">
                                    Nominal: <strong>Rp{{ number_format($fine->amount, 0, ',', '.') }}</strong>
                                </p>
                                <p class="text-xs text-[#B9882F] bg-[#FDF3E3] rounded-lg px-3 py-2 mb-5">
                                    ⚠️ Pastikan pembayaran tunai sudah diterima sebelum konfirmasi.
                                </p>
                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                            x-data="{}"
                                            x-on:click="$dispatch('close-modal', 'confirm-paid-{{ $fine->id }}')"
                                            class="px-4 py-2 rounded-xl text-sm text-[#6B7280] hover:bg-black/5">
                                        Batal
                                    </button>
                                    <form method="POST" action="{{ route('admin.fines.markPaid', $fine) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-4 py-2 rounded-xl text-sm bg-[#16331F] text-white hover:bg-[#0f2114] transition">
                                            Ya, Tandai Lunas
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </x-modal>
                    @endif

                @endforeach
            </tbody>
        </table>

        @if ($fines->hasPages())
            <x-slot name="footer">
                {{ $fines->links() }}
            </x-slot>
        @endif

    </x-table-wrapper>

</x-app-layout>