@extends('layouts.member')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman')

@section('topbar-search')
    <form method="GET" action="{{ route('member.loans.history') }}" class="relative flex items-center gap-2">
        <input type="hidden" name="status" value="{{ $status }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search my history..."
               class="w-72 pl-9 pr-4 py-2 rounded-full bg-white border border-stone-200 text-sm
                      placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/30">
    </form>
@endsection

@section('content')

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-[#DCEBD9] text-[#2F5233] text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ===== TAB FILTER STATUS ===== --}}
    @php
        $tabs = [
            'all'      => 'Semua',
            'pending'  => 'Menunggu',
            'active'   => 'Dipinjam',
            'overdue'  => 'Terlambat',
            'returned' => 'Selesai',
        ];
    @endphp

    <div class="flex flex-wrap gap-2 mb-6">
        @foreach ($tabs as $key => $label)
            <a href="{{ route('member.loans.history', array_filter(['status' => $key, 'q' => request('q')])) }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition
                      {{ $status === $key
                            ? 'bg-[#1D3B2C] text-white'
                            : 'bg-white text-stone-600 border border-stone-200 hover:border-[#1D3B2C]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-stone-400 uppercase text-xs bg-[#F7F5EF]">
                    <th class="py-3 px-6">Buku</th>
                    <th class="py-3 px-4">Diajukan</th>
                    <th class="py-3 px-4">Jatuh Tempo</th>
                    <th class="py-3 px-4">Dikembalikan</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-6">Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($loans as $loan)
                    @php
                        $cover = $loan->book->cover
                            ? asset('storage/' . $loan->book->cover)
                            : 'https://placehold.co/80x100/2f4f3f/e8dcc4?text=' . urlencode($loan->book->title);
                    @endphp
                    <tr class="border-t border-stone-100">
                        <td class="py-3 px-6">
                            <div class="flex items-center gap-3">
                                <img src="{{ $cover }}" alt="{{ $loan->book->title }}"
                                     class="w-10 h-12 object-cover rounded-lg shrink-0">
                                <div>
                                    <p class="font-medium text-stone-800">{{ $loan->book->title }}</p>
                                    <p class="text-xs text-stone-400">{{ $loan->book->category->name ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-stone-600">{{ $loan->created_at->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-stone-600">{{ $loan->due_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="py-3 px-4 text-stone-600">{{ $loan->returned_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="py-3 px-4"><x-badge-status :status="$loan->status" /></td>
                        <td class="py-3 px-6 {{ $loan->fine ? 'text-red-600 font-medium' : 'text-stone-400' }}">
                            {{ $loan->fine ? 'Rp' . number_format($loan->fine->amount, 0, ',', '.') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-stone-400">
                            @if ($status === 'all' && !request('q'))
                                Kamu belum pernah meminjam buku.
                                <a href="{{ route('member.catalog.index') }}" class="text-[#2F5233] font-medium hover:underline">
                                    Jelajahi katalog
                                </a>
                            @else
                                Tidak ada riwayat yang cocok dengan filter ini.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $loans->links() }}
    </div>

@endsection