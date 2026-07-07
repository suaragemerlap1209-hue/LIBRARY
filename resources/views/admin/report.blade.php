<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-widest mb-0.5">Manajemen</p>
            <h2 class="text-xl font-bold text-[#1F2937]">Laporan</h2>
        </div>
    </x-slot>

    <div class="space-y-5">

        {{-- Filter periode + export --}}
        <x-card>
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-[#374151] mb-1.5">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#374151] mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           class="rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                </div>
                <button type="submit" class="bg-black/5 text-[#374151] text-sm font-medium px-4 py-2 rounded-xl hover:bg-black/10 transition">
                    Terapkan
                </button>

                <div class="ml-auto flex gap-2">
                    <a href="{{ route('admin.reports.export', ['type' => 'excel', 'from' => request('from'), 'to' => request('to')]) }}"
                       class="bg-[#EAF3DE] text-[#3B6D11] text-sm font-medium px-4 py-2 rounded-xl hover:bg-[#dcecc9] transition inline-flex items-center gap-2">
                        <i class="fa-solid fa-file-excel text-xs"></i> Excel
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'pdf', 'from' => request('from'), 'to' => request('to')]) }}"
                       class="bg-[#FCEBEB] text-[#A32D2D] text-sm font-medium px-4 py-2 rounded-xl hover:bg-[#f9d9d9] transition inline-flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf text-xs"></i> PDF
                    </a>
                </div>
            </form>
        </x-card>

        {{-- Kartu ringkasan statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-[#16331F]/10 text-[#16331F] flex items-center justify-center">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div>
                        <p class="text-xs text-[#9CA3AF]">Total Buku</p>
                        <p class="text-xl font-bold text-[#1F2937]">{{ $totalBooks ?? 0 }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-[#B9882F]/10 text-[#B9882F] flex items-center justify-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <p class="text-xs text-[#9CA3AF]">Anggota Aktif</p>
                        <p class="text-xl font-bold text-[#1F2937]">{{ $activeMembers ?? 0 }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-[#3B6D11]/10 text-[#3B6D11] flex items-center justify-center">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                    <div>
                        <p class="text-xs text-[#9CA3AF]">Peminjaman (Periode Ini)</p>
                        <p class="text-xl font-bold text-[#1F2937]">{{ $totalLoans ?? 0 }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-[#A32D2D]/10 text-[#A32D2D] flex items-center justify-center">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div>
                        <p class="text-xs text-[#9CA3AF]">Denda Terkumpul</p>
                        <p class="text-xl font-bold text-[#1F2937]">
                            Rp{{ number_format($totalFinesCollected ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Grafik peminjaman per bulan --}}
        <x-card title="Peminjaman per Bulan">
            <canvas id="loanChart" height="90"></canvas>
        </x-card>

        {{-- Tabel detail transaksi --}}
        <x-table-wrapper :is-empty="empty($transactions ?? [])" empty-text="Tidak ada data pada periode ini.">
            <x-slot name="filters">
                <p class="text-sm font-semibold text-[#1F2937]">Riwayat Transaksi</p>
            </x-slot>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-black/5">
                        <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Tanggal</th>
                        <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Anggota</th>
                        <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Buku</th>
                        <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Jatuh Tempo</th>
                        <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Status</th>
                        <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @foreach($transactions ?? [] as $trx)
                        <tr class="hover:bg-black/[0.01] transition">
                            <td class="py-4 text-[#6B7280]">{{ $trx->borrowed_at?->format('d M Y') ?? '-' }}</td>
                            <td class="py-4 font-medium text-[#1F2937]">{{ $trx->user->name ?? '-' }}</td>
                            <td class="py-4 text-[#6B7280]">{{ $trx->book->title ?? '-' }}</td>
                            <td class="py-4 text-[#6B7280]">{{ $trx->due_at?->format('d M Y') ?? '-' }}</td>
                            <td class="py-4">
                                @if($trx->returned_at)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#EAF3DE] text-[#3B6D11]">
                                        Dikembalikan
                                    </span>
                                @elseif($trx->due_at && $trx->due_at->isPast())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FCEBEB] text-[#A32D2D]">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FDF3E3] text-[#B9882F]">
                                        Dipinjam
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 text-right font-medium text-[#1F2937]">
                                {{ $trx->fine ? 'Rp' . number_format($trx->fine->amount, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(isset($transactions) && method_exists($transactions, 'links'))
                <x-slot name="footer">
                    {{ $transactions->links() }}
                </x-slot>
            @endif
        </x-table-wrapper>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('loanChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels ?? []),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($chartData ?? []),
                    backgroundColor: '#16331F',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>