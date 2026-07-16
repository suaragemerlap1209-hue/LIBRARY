<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-widest mb-0.5">Admin</p>
            <h2 class="text-xl font-bold text-[#1F2937]">Manajemen Peminjaman</h2>
        </div>
    </x-slot>

    <div class="space-y-5">

        @if(session('success'))
            <div class="bg-[#EAF3DE] text-[#3B6D11] text-sm rounded-xl px-4 py-3">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-[#FCEBEB] text-[#A32D2D] text-sm rounded-xl px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-wrap gap-2">
            @php
                $tabs = [
                    'pending'  => 'Menunggu',
                    'active'   => 'Aktif',
                    'overdue'  => 'Terlambat',
                    'returned' => 'Selesai',
                    'all'      => 'Semua',
                ];
            @endphp
            @foreach($tabs as $key => $label)
                <a href="{{ route('admin.loans.index', array_filter(['status' => $key, 'search' => $search])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition
                          {{ $status === $key ? 'bg-[#16331F] text-white' : 'bg-white text-[#6B7280] border border-black/5 hover:bg-black/[0.02]' }}">
                    {{ $label }}
                    @if(isset($counts[$key]))
                        <span class="ml-1 text-xs {{ $status === $key ? 'text-white/70' : 'text-[#9CA3AF]' }}">
                            ({{ $counts[$key] }})
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-black/5 p-4">
            <form method="GET" action="{{ route('admin.loans.index') }}" class="flex gap-3">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#9CA3AF] text-xs"></i>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari nama anggota, nomor anggota, atau judul buku..."
                           class="w-full rounded-xl border-black/10 text-sm pl-10 focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                </div>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm bg-[#16331F] text-white font-medium hover:bg-[#1F4429] transition">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.loans.index', ['status' => $status]) }}"
                       class="px-4 py-2.5 rounded-xl text-sm text-[#6B7280] hover:bg-black/5 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-black/5 bg-[#F4F1EB]/50">
                            <th class="text-left px-6 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Anggota</th>
                            <th class="text-left px-4 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Judul Buku</th>
                            <th class="text-left px-4 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Tanggal Pinjam</th>
                            <th class="text-left px-4 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Jatuh Tempo</th>
                            <th class="text-left px-4 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Status</th>
                            <th class="text-center px-4 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-black/[0.01] transition">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#16331F]/10 flex items-center justify-center shrink-0">
                                            <span class="text-[10px] font-bold text-[#16331F]">
                                                {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-[#1F2937]">{{ $loan->user->name }}</p>
                                            <p class="text-[10px] text-[#9CA3AF]">{{ $loan->user->member_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-medium text-[#1F2937]">{{ Str::limit($loan->book->title, 28) }}</p>
                                    <p class="text-[10px] text-[#9CA3AF]">{{ $loan->book->author }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-[#6B7280]">
                                        {{ $loan->borrowed_at ? $loan->borrowed_at->translatedFormat('d M Y') : '—' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-[#6B7280]">
                                        {{ $loan->due_at ? $loan->due_at->translatedFormat('d M Y') : '—' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $badgeMap = [
                                            'pending'  => 'bg-[#FEF3C7] text-[#B45309]',
                                            'active'   => 'bg-[#DBEAFE] text-[#1D4ED8]',
                                            'overdue'  => 'bg-[#FCEBEB] text-[#A32D2D]',
                                            'returned' => 'bg-[#EAF3DE] text-[#3B6D11]',
                                        ];
                                        $labelMap = [
                                            'pending'  => 'Menunggu',
                                            'active'   => 'Aktif',
                                            'overdue'  => 'Terlambat',
                                            'returned' => 'Selesai',
                                        ];
                                    @endphp
                                    <span class="text-[11px] font-medium px-2.5 py-1 rounded-full {{ $badgeMap[$loan->status] ?? 'bg-black/5 text-[#6B7280]' }}">
                                        {{ $labelMap[$loan->status] ?? ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($loan->status === 'pending')
                                            <form method="POST" action="{{ route('admin.loans.approve', $loan) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="w-8 h-8 rounded-full bg-[#EAF3DE] hover:bg-[#16331F] text-[#3B6D11] hover:text-white flex items-center justify-center transition" title="Setujui">
                                                    <i class="fa-solid fa-check text-xs"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.loans.decline', $loan) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="w-8 h-8 rounded-full bg-[#FCEBEB] hover:bg-[#A32D2D] text-[#A32D2D] hover:text-white flex items-center justify-center transition" title="Tolak">
                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                </button>
                                            </form>
                                        @elseif($loan->status === 'active' || $loan->status === 'overdue')
                                            <form method="POST" action="{{ route('admin.loans.return', $loan) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="px-3 py-1.5 rounded-lg text-[11px] font-medium bg-[#16331F]/10 text-[#16331F] hover:bg-[#16331F] hover:text-white transition">
                                                    Tandai Kembali
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[11px] text-[#9CA3AF]">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center">
                                    <i class="fa-regular fa-folder-open text-2xl text-[#D1D5DB] mb-2 block"></i>
                                    <p class="text-sm text-[#6B7280]">Tidak ada data peminjaman.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($loans->hasPages())
                <div class="px-6 py-4 border-t border-black/5">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>