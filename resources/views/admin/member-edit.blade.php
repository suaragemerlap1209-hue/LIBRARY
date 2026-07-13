<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('admin.members.show', $member) }}" class="inline-flex items-center gap-1.5 text-xs text-[#9CA3AF] hover:text-[#16331F] transition mb-2">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali
        </a>
        <h2 class="text-xl font-bold text-[#1F2937]">Edit Anggota</h2>
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('admin.members.update', $member) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-3 pb-4 border-b border-black/5">
                    <div class="w-11 h-11 rounded-full bg-[#16331F] text-white flex items-center justify-center text-sm font-bold shrink-0">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#1F2937]">{{ $member->name }}</p>
                        <p class="text-xs text-[#9CA3AF] font-mono">{{ $member->member_id ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $member->name) }}"
                           class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                    @error('name')
                        <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $member->email) }}"
                               class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        @error('email')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="birth_date"
                               value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}"
                               class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        @error('birth_date')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-1.5">Alamat</label>
                    <input type="text" name="address" value="{{ old('address', $member->address) }}"
                           placeholder="Opsional"
                           class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                    @error('address')
                        <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">Status</label>
                        <select name="status" class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                            <option value="active" @selected(old('status', $member->status) === 'active')>Aktif</option>
                            <option value="suspended" @selected(old('status', $member->status) === 'suspended')>Ditangguhkan</option>
                            <option value="blocked" @selected(old('status', $member->status) === 'blocked')>Diblokir</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">Maks Pinjam</label>
                        <input type="number" name="max_loans" min="1" max="20"
                               value="{{ old('max_loans', $member->max_loans) }}"
                               class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        @error('max_loans')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">Berlaku Sampai</label>
                        <input type="date" name="expired_at"
                               value="{{ old('expired_at', $member->expired_at?->format('Y-m-d')) }}"
                               class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        @error('expired_at')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('admin.members.show', $member) }}"
                       class="px-4 py-2.5 rounded-xl text-sm text-[#6B7280] hover:bg-black/5 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-sm bg-[#16331F] text-white font-medium hover:bg-[#1F4429] transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>