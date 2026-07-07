<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-widest mb-0.5">Manajemen</p>
            <h2 class="text-xl font-bold text-[#1F2937]">Kelola Anggota</h2>
        </div>
    </x-slot>

    <x-table-wrapper :is-empty="empty($members ?? [])" empty-text="Belum ada data anggota.">
        <x-slot name="filters">
            <form method="GET" class="flex gap-2 flex-1 max-w-lg min-w-[280px]">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF] text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau member ID..."
                           class="w-full pl-9 rounded-xl border-black/10 text-sm text-[#1F2937] placeholder:text-[#9CA3AF] focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                </div>
                <select name="status" class="rounded-xl border-black/10 text-sm text-[#374151] focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Ditangguhkan</option>
                    <option value="blocked" @selected(request('status') === 'blocked')>Diblokir</option>
                </select>
                <button type="submit" class="bg-black/5 text-[#374151] text-sm font-medium px-4 py-2 rounded-xl hover:bg-black/10 transition">
                    Filter
                </button>
            </form>

            {{-- Tombol "Tambah Anggota" DIHAPUS — tidak ada fitur create, sesuai keputusan tim --}}
        </x-slot>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-black/5">
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Member ID</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Nama</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Email</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Usia</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Maks Pinjam</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Status</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @foreach($members as $member)
                    <tr class="hover:bg-black/[0.01] transition">
                        <td class="py-4 text-[#6B7280]">{{ $member->member_id }}</td>
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#16331F]/10 text-[#16331F] flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-[#1F2937]">{{ $member->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-[#6B7280]">{{ $member->email }}</td>
                        <td class="py-4 text-[#6B7280]">{{ $member->birth_date?->age ?? '-' }} tahun</td>
                        <td class="py-4 text-[#6B7280]">{{ $member->max_loans }} buku</td>
                        <td class="py-4"><x-status-badge :status="$member->status" /></td>
                        <td class="py-4 text-right">
                            <a href="{{ route('admin.members.show', $member) }}" class="text-[#B9882F] font-semibold hover:underline text-xs">Detail</a>
                            {{-- Link "Edit" DIHAPUS — ubah status dilakukan di halaman detail --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(isset($members) && method_exists($members, 'links'))
            <x-slot name="footer">
                {{ $members->links() }}
            </x-slot>
        @endif
    </x-table-wrapper>
</x-app-layout>