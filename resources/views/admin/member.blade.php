<x-app-layout>
    <x-slot name="header">
        <p class="text-xs uppercase tracking-widest text-[#9CA3AF] mb-1">Manajemen</p>
        <h2 class="text-xl font-bold text-[#1F2937]">Manajemen Anggota</h2>
    </x-slot>

    {{-- ==================== STAT CARDS ==================== --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-card>
            <p class="text-xs text-[#9CA3AF] mb-1">Total Anggota</p>
            <p class="text-2xl font-bold text-[#1F2937]">{{ $totalMembers }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-[#9CA3AF] mb-1">Aktif</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $activeMembers }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-[#9CA3AF] mb-1">Ditangguhkan</p>
            <p class="text-2xl font-bold text-amber-600">{{ $suspendedMembers }}</p>
        </x-card>
        <x-card>
            <p class="text-xs text-[#9CA3AF] mb-1">Diblokir</p>
            <p class="text-2xl font-bold text-red-600">{{ $blockedMembers }}</p>
        </x-card>
    </div>

    {{-- ==================== TAB ROLE ==================== --}}
    <div class="flex gap-2 mb-6">
        <a href="{{ route('admin.members.index', ['role' => 'member']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition
                  {{ $roleFilter === 'member' ? 'bg-[#16331F] text-white' : 'bg-white border border-black/10 text-[#6B7280] hover:bg-black/5' }}">
            Member ({{ $totalMembers }})
        </a>
        <a href="{{ route('admin.members.index', ['role' => 'admin']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition
                  {{ $roleFilter === 'admin' ? 'bg-[#16331F] text-white' : 'bg-white border border-black/10 text-[#6B7280] hover:bg-black/5' }}">
            Admin ({{ $totalAdmins }})
        </a>
    </div>

    {{-- ==================== FILTER & SEARCH ==================== --}}
    <x-card no-padding class="mb-6">
        <form method="GET" action="{{ route('admin.members.index') }}" class="p-5 flex flex-col md:flex-row gap-3">
            <input type="hidden" name="role" value="{{ $roleFilter }}">

            <div class="relative flex-1">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF]">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau ID anggota..."
                       class="w-full border border-black/10 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
            </div>

            <select name="status" class="border border-black/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="suspended" @selected(request('status') === 'suspended')>Ditangguhkan</option>
                <option value="blocked" @selected(request('status') === 'blocked')>Diblokir</option>
            </select>

            <button type="submit" class="bg-[#16331F] text-white text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-[#1F4429] transition">
                Filter
            </button>

            @if (request('search') || request('status'))
                <a href="{{ route('admin.members.index', ['role' => $roleFilter]) }}" class="text-sm text-[#9CA3AF] px-3 py-2.5 hover:text-[#1F2937] transition">
                    Reset
                </a>
            @endif
        </form>
    </x-card>

    {{-- ==================== TABEL ANGGOTA ==================== --}}
    <x-card no-padding>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wide text-[#9CA3AF] border-b border-black/5">
                        <th class="px-5 py-3 font-medium">Anggota</th>
                        <th class="px-5 py-3 font-medium">ID</th>
                        <th class="px-5 py-3 font-medium">Email</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Bergabung</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($members as $member)
                        <tr class="hover:bg-black/[0.02] transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#16331F] text-white flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-[#1F2937]">{{ $member->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-[#6B7280] font-mono text-xs">{{ $member->member_id ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-[#6B7280]">{{ $member->email }}</td>
                            <td class="px-5 py-3.5">
                                <x-status-badge :status="$member->status" />
                            </td>
                            <td class="px-5 py-3.5 text-[#6B7280]">{{ $member->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.members.show', $member) }}"
                                   class="text-xs font-medium text-[#16331F] hover:underline">
                                    Lihat Detail
                                </a>
                                <a href="{{ route('admin.members.edit', $member) }}"
                                   class="text-xs font-medium text-[#16331F] hover:underline ml-3">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-[#9CA3AF] italic text-sm">
                                Belum ada {{ $roleFilter === 'admin' ? 'admin' : 'anggota' }} terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($members->hasPages())
            <div class="px-5 py-4 border-t border-black/5">
                {{ $members->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>