<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-1.5 text-xs text-[#9CA3AF] hover:text-[#16331F] transition mb-2">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali
        </a>
        <h2 class="text-xl font-bold text-[#1F2937]">Detail Anggota</h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

       <x-card no-padding class="lg:col-span-1">
            <div class="p-6">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-[#16331F] text-white flex items-center justify-center text-2xl font-bold mx-auto">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    <h3 class="mt-4 font-semibold text-[#1F2937]">{{ $member->name }}</h3>
                    <p class="text-xs text-[#9CA3AF]">{{ $member->member_id }}</p>
                    <div class="mt-2 flex justify-center gap-2">
                        <x-status-badge :status="$member->status" />
                        <span class="text-[11px] font-semibold px-2.5 py-1 rounded-md border border-[#16331F]/20 text-[#16331F] bg-[#16331F]/5">
                            {{ ucfirst($member->role) }}
                        </span>
                    </div>
                </div>

                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-black/5"><dt class="text-[#9CA3AF]">Email</dt><dd class="text-[#1F2937] font-medium">{{ $member->email }}</dd></div>
                    <div class="flex justify-between py-2 border-b border-black/5"><dt class="text-[#9CA3AF]">Tanggal Lahir</dt><dd class="text-[#1F2937] font-medium">{{ $member->birth_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between py-2 border-b border-black/5"><dt class="text-[#9CA3AF]">Usia</dt><dd class="text-[#1F2937] font-medium">{{ $member->birth_date?->age ?? '-' }} tahun</dd></div>
                    <div class="flex justify-between py-2 border-b border-black/5"><dt class="text-[#9CA3AF]">Maks Pinjam</dt><dd class="text-[#1F2937] font-medium">{{ $member->max_loans }} buku</dd></div>
                    <div class="flex justify-between py-2 border-b border-black/5"><dt class="text-[#9CA3AF]">Berlaku Sampai</dt><dd class="text-[#1F2937] font-medium">{{ $member->expired_at?->format('d M Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between py-2"><dt class="text-[#9CA3AF]">Bergabung Sejak</dt><dd class="text-[#1F2937] font-medium">{{ $member->created_at->format('d M Y') }}</dd></div>
                </dl>

                <div class="mt-6 space-y-2">
                    <button type="button" x-data="{}" x-on:click="$dispatch('open-modal', 'confirm-status')"
                            class="w-full bg-[#FDF3E3] text-[#B9882F] text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-[#f9e6c7] transition">
                        Ubah Status
                    </button>

                    @if ($member->id !== auth()->id())
                        <button type="button" x-data="{}" x-on:click="$dispatch('open-modal', 'confirm-role')"
                                class="w-full bg-[#EEF2FF] text-[#4338CA] text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-[#e0e7ff] transition">
                            Ubah Role
                        </button>
                    @endif
                </div>
            </div>
        </x-card>

        <div class="lg:col-span-2 space-y-5">
            <x-card title="Riwayat Peminjaman">
                <div class="text-center py-8">
                    <i class="fa-regular fa-clock text-2xl text-[#D1D5DB] mb-2 block"></i>
                    <p class="text-sm text-[#9CA3AF] italic">Modul Peminjaman belum tersedia.</p>
                </div>
            </x-card>
            <x-card title="Riwayat Denda">
                <div class="text-center py-8">
                    <i class="fa-regular fa-clock text-2xl text-[#D1D5DB] mb-2 block"></i>
                    <p class="text-sm text-[#9CA3AF] italic">Modul Denda belum tersedia.</p>
                </div>
            </x-card>
        </div>
    </div>

    {{-- Modal: Ubah Status --}}
    <x-modal name="confirm-status" :show="false" max-width="md">
        <div class="p-6">
            <h3 class="text-sm font-semibold text-[#1F2937] mb-2">Ubah Status Anggota?</h3>
            <p class="text-xs text-[#6B7280] mb-5">Tindakan ini akan mengubah akses "{{ $member->name }}" ke sistem.</p>
            <form method="POST" action="{{ route('admin.members.updateStatus', $member) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <select name="status" class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                    <option value="active" @selected($member->status === 'active')>Aktif</option>
                    <option value="suspended" @selected($member->status === 'suspended')>Ditangguhkan</option>
                    <option value="blocked" @selected($member->status === 'blocked')>Diblokir</option>
                </select>
                <div class="flex justify-end gap-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'confirm-status')"
                            class="px-4 py-2 rounded-xl text-sm text-[#6B7280] hover:bg-black/5">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-sm bg-[#16331F] text-white hover:bg-[#1F4429]">Ya, Ubah</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Modal: Ubah Role --}}
    @if ($member->id !== auth()->id())
        <x-modal name="confirm-role" :show="false" max-width="md">
            <div class="p-6">
                <h3 class="text-sm font-semibold text-[#1F2937] mb-2">Ubah Role Anggota?</h3>
                <p class="text-xs text-[#6B7280] mb-5">
                    Mengubah role "{{ $member->name }}" akan langsung memengaruhi hak akses mereka di sistem.
                    Berhati-hatilah sebelum menjadikan seseorang Admin.
                </p>
                <form method="POST" action="{{ route('admin.members.updateRole', $member) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <select name="role" class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        <option value="member" @selected($member->role === 'member')>Member</option>
                        <option value="admin" @selected($member->role === 'admin')>Admin</option>
                    </select>
                    <div class="flex justify-end gap-2">
                        <button type="button" x-on:click="$dispatch('close-modal', 'confirm-role')"
                                class="px-4 py-2 rounded-xl text-sm text-[#6B7280] hover:bg-black/5">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-sm bg-[#16331F] text-white hover:bg-[#1F4429]">Ya, Ubah</button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif
</x-app-layout>