<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-widest mb-0.5">Manajemen</p>
            <h2 class="text-xl font-bold text-[#1F2937]">Verifikasi Pembayaran</h2>
        </div>
    </x-slot>

    <x-table-wrapper :is-empty="empty($receipts ?? [])" empty-text="Belum ada bukti pembayaran yang masuk.">
        <x-slot name="filters">
            <form method="GET" class="flex gap-2 flex-1 max-w-md min-w-[240px]">
                <select name="status" class="rounded-xl border-black/10 text-sm text-[#374151] focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                    <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                </select>
                <button type="submit" class="bg-black/5 text-[#374151] text-sm font-medium px-4 py-2 rounded-xl hover:bg-black/10 transition">
                    Filter
                </button>
            </form>
        </x-slot>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-black/5">
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Anggota</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Buku</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Nominal Denda</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Bukti Bayar</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Diajukan</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Status</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @foreach($receipts as $receipt)
                    <tr class="hover:bg-black/[0.01] transition">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#16331F]/10 text-[#16331F] flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($receipt->fine->loan->user->name ?? '-', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-[#1F2937]">{{ $receipt->fine->loan->user->name ?? '-' }}</p>
                                    <p class="text-xs text-[#9CA3AF]">{{ $receipt->fine->loan->user->member_id ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-[#6B7280]">{{ $receipt->fine->loan->book->title ?? '-' }}</td>
                        <td class="py-4 font-medium text-[#1F2937]">
                            Rp{{ number_format($receipt->fine->amount ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="py-4">
                            <button type="button" x-data="{}"
                                    x-on:click="$dispatch('open-modal', 'view-receipt-{{ $receipt->id }}')"
                                    class="text-[#B9882F] font-semibold hover:underline text-xs inline-flex items-center gap-1.5">
                                <i class="fa-regular fa-image text-xs"></i> Lihat Bukti
                            </button>
                        </td>
                        <td class="py-4 text-[#6B7280]">{{ $receipt->created_at->format('d M Y') }}</td>
                        <td class="py-4">
                            @switch($receipt->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FDF3E3] text-[#B9882F]">
                                        Menunggu
                                    </span>
                                    @break
                                @case('approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#EAF3DE] text-[#3B6D11]">
                                        Disetujui
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FCEBEB] text-[#A32D2D]">
                                        Ditolak
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td class="py-4 text-right">
                            @if($receipt->status === 'pending')
                                <form method="POST" action="{{ route('admin.payments.approve', $receipt) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[#3B6D11] font-semibold hover:underline text-xs mr-3">
                                        Setujui
                                    </button>
                                </form>
                                <button type="button" x-data="{}"
                                        x-on:click="$dispatch('open-modal', 'reject-receipt-{{ $receipt->id }}')"
                                        class="text-[#A32D2D] font-semibold hover:underline text-xs">
                                    Tolak
                                </button>
                            @else
                                <span class="text-xs text-[#9CA3AF]">-</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Modal lihat bukti bayar --}}
                    <x-modal name="view-receipt-{{ $receipt->id }}" :show="false" max-width="md">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-[#1F2937] mb-4">
                                Bukti Pembayaran — {{ $receipt->fine->loan->user->name ?? '-' }}
                            </h3>
                            <img src="{{ asset('storage/' . $receipt->file_path) }}" alt="Bukti pembayaran"
                                 class="w-full rounded-xl border border-black/5">
                            <div class="flex justify-end mt-5">
                                <button type="button" x-data="{}" x-on:click="$dispatch('close-modal', 'view-receipt-{{ $receipt->id }}')"
                                        class="px-4 py-2 rounded-xl text-sm text-[#6B7280] hover:bg-black/5">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </x-modal>

                    {{-- Modal konfirmasi tolak --}}
                    <x-modal name="reject-receipt-{{ $receipt->id }}" :show="false" max-width="sm">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-[#1F2937] mb-2">Tolak Bukti Pembayaran?</h3>
                            <p class="text-xs text-[#6B7280] mb-5">
                                Anggota perlu mengunggah ulang bukti pembayaran yang valid.
                            </p>
                            <form method="POST" action="{{ route('admin.payments.reject', $receipt) }}" class="flex justify-end gap-2">
                                @csrf
                                @method('PATCH')
                                <button type="button" x-data="{}" x-on:click="$dispatch('close-modal', 'reject-receipt-{{ $receipt->id }}')"
                                        class="px-4 py-2 rounded-xl text-sm text-[#6B7280] hover:bg-black/5">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 rounded-xl text-sm bg-[#A32D2D] text-white hover:bg-[#8a2424]">
                                    Ya, Tolak
                                </button>
                            </form>
                        </div>
                    </x-modal>
                @endforeach
            </tbody>
        </table>

        @if(isset($receipts) && method_exists($receipts, 'links'))
            <x-slot name="footer">
                {{ $receipts->links() }}
            </x-slot>
        @endif
    </x-table-wrapper>
</x-app-layout>