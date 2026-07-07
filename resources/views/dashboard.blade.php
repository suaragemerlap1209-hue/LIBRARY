<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-widest mb-0.5">Selamat Pagi</p>
            <h2 class="text-xl font-bold text-[#1F2937]">Dasbor Administrasi</h2>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 bg-white border border-black/5 rounded-full px-4 py-2 text-sm text-[#9CA3AF]">
                <i class="fa-regular fa-calendar text-xs"></i>
                {{ now()->translatedFormat('l, d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#16331F]/10 flex items-center justify-center">
                        <i class="fa-solid fa-book-open text-[#16331F]"></i>
                    </div>
                    <span class="text-[11px] font-medium text-[#3B6D11] bg-[#EAF3DE] px-2 py-0.5 rounded-full">
                        +{{ $newBooksThisWeek ?? 0 }} minggu ini
                    </span>
                </div>
                <p class="text-[11px] text-[#9CA3AF] uppercase tracking-wide font-medium mb-1">Total Koleksi</p>
                <p class="text-2xl font-bold text-[#1F2937]">{{ number_format($totalBooks ?? 0, 0, ',', '.') }}</p>
                <div class="mt-3 h-1 bg-black/5 rounded-full overflow-hidden">
                    <div class="h-full w-3/4 bg-[#16331F] rounded-full"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#2563EB]/10 flex items-center justify-center">
                        <i class="fa-solid fa-users text-[#2563EB]"></i>
                    </div>
                    <span class="text-[11px] font-medium text-[#1D4ED8] bg-[#DBEAFE] px-2 py-0.5 rounded-full">
                        +{{ $newMembersThisMonth ?? 0 }} bulan ini
                    </span>
                </div>
                <p class="text-[11px] text-[#9CA3AF] uppercase tracking-wide font-medium mb-1">Anggota Aktif</p>
                <p class="text-2xl font-bold text-[#1F2937]">{{ number_format($activeMembers ?? 0, 0, ',', '.') }}</p>
                <div class="mt-3 h-1 bg-black/5 rounded-full overflow-hidden">
                    <div class="h-full w-4/5 bg-[#2563EB] rounded-full"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#E8B36A]/15 flex items-center justify-center">
                        <i class="fa-solid fa-file-lines text-[#B9882F]"></i>
                    </div>
                    <span class="text-[11px] font-medium text-[#6B7280] bg-black/5 px-2 py-0.5 rounded-full">Hari ini</span>
                </div>
                <p class="text-[11px] text-[#9CA3AF] uppercase tracking-wide font-medium mb-1">Peminjaman Harian</p>
                <p class="text-2xl font-bold text-[#1F2937]">{{ $dailyLoans ?? 0 }}</p>
                <div class="mt-3 h-1 bg-black/5 rounded-full overflow-hidden">
                    <div class="h-full w-1/2 bg-[#E8B36A] rounded-full"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#A32D2D]/10 flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-[#A32D2D]"></i>
                    </div>
                    <span class="text-[11px] font-medium text-[#A32D2D] bg-[#FCEBEB] px-2 py-0.5 rounded-full">
                        {{ $overdueToday ?? 0 }} terlambat
                    </span>
                </div>
                <p class="text-[11px] text-[#9CA3AF] uppercase tracking-wide font-medium mb-1">Denda Tertunda</p>
                <p class="text-2xl font-bold text-[#A32D2D]">Rp{{ number_format($dendaTertunda ?? 0, 0, ',', '.') }}</p>
                <div class="mt-3 h-1 bg-black/5 rounded-full overflow-hidden">
                    <div class="h-full w-1/3 bg-[#A32D2D] rounded-full"></div>
                </div>
            </div>

        </div>

        {{-- Verifikasi + Aktivitas --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Verifikasi Peminjaman --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-black/5 overflow-hidden">
                <div class="px-6 py-4 border-b border-black/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-[#1F2937]">Verifikasi Pembayaran</h3>
                        <p class="text-xs text-[#9CA3AF]">Bukti pembayaran denda yang menunggu konfirmasi.</p>
                    </div>
                    <a href="{{ Route::has('admin.payments.index') ? route('admin.payments.index') : '#' }}"
                       class="text-xs font-semibold text-[#16331F] hover:underline">
                        Lihat Semua <i class="fa-solid fa-chevron-right text-[10px] ml-0.5"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-black/5 bg-[#F4F1EB]/50">
                                <th class="text-left px-6 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Anggota</th>
                                <th class="text-left px-4 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Judul Buku</th>
                                <th class="text-left px-4 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Tanggal</th>
                                <th class="text-center px-4 py-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @forelse($pendingLoans ?? [] as $loan)
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
                                        <p class="text-xs text-[#6B7280]">{{ $loan->created_at->translatedFormat('d M Y') }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <form method="POST" action="#">
                                                @csrf
                                                <button class="w-8 h-8 rounded-full bg-[#EAF3DE] hover:bg-[#16331F] text-[#3B6D11] hover:text-white flex items-center justify-center transition">
                                                    <i class="fa-solid fa-check text-xs"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="#">
                                                @csrf @method('DELETE')
                                                <button class="w-8 h-8 rounded-full bg-[#FCEBEB] hover:bg-[#A32D2D] text-[#A32D2D] hover:text-white flex items-center justify-center transition">
                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center">
                                        <i class="fa-regular fa-folder-open text-2xl text-[#D1D5DB] mb-2 block"></i>
                                        <p class="text-sm text-[#6B7280]">Tidak ada permintaan yang menunggu.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        {{-- Chart Sirkulasi --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-[#1F2937]">Tren Sirkulasi Mingguan</h3>
                    <p class="text-xs text-[#9CA3AF] mt-0.5">Statistik penggunaan koleksi dan sirkulasi buku per minggu.</p>
                </div>
                <select class="text-xs border border-black/10 rounded-lg px-3 py-1.5 text-[#374151] bg-white focus:outline-none focus:ring-2 focus:ring-[#16331F]/20">
                    <option>Minggu ini</option>
                    <option>Bulan ini</option>
                </select>
            </div>

            @php
                $chartData = $weeklyCirculation ?? [
                    ['label' => 'Sen', 'value' => 40],
                    ['label' => 'Sel', 'value' => 70],
                    ['label' => 'Rab', 'value' => 35],
                    ['label' => 'Kam', 'value' => 90],
                    ['label' => 'Jum', 'value' => 55],
                    ['label' => 'Sab', 'value' => 100],
                    ['label' => 'Min', 'value' => 65],
                ];
                $max = collect($chartData)->max('value') ?: 1;
            @endphp

            <div class="flex items-end justify-between gap-2 h-36">
                @foreach($chartData as $day)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full rounded-lg bg-[#16331F]/10 relative overflow-hidden" style="height: 120px;">
                            <div class="absolute bottom-0 left-0 right-0 bg-[#16331F] rounded-lg"
                                 style="height: {{ ($day['value'] / $max) * 100 }}%"></div>
                        </div>
                        <span class="text-[11px] text-[#9CA3AF]">{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>