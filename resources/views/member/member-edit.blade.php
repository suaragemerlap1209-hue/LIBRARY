<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('admin.members.show', $member) }}" class="inline-flex items-center gap-1.5 text-xs text-[#9CA3AF] hover:text-[#16331F] transition mb-2">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Detail
        </a>
        <h2 class="text-xl font-bold text-[#1F2937]">Edit Data Anggota</h2>
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('admin.members.update', $member) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#374151] mb-1.5">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $member->name) }}" required
                               class="w-full border border-black/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-[#374151] mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $member->email) }}" required
                               class="w-full border border-black/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-[#374151] mb-1.5">Tanggal Lahir</label>
                        <input id="birth_date" name="birth_date" type="date"
                               value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}" required
                               class="w-full border border-black/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="max_loans" class="block text-sm font-medium text-[#374151] mb-1.5">Maks. Buku Dipinjam</label>
                        <input id="max_loans" name="max_loans" type="number" min="1" max="20"
                               value="{{ old('max_loans', $member->max_loans) }}" required
                               class="w-full border border-black/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        <x-input-error :messages="$errors->get('max_loans')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-[#374151] mb-1.5">Status</label>
                        <select id="status" name="status" required
                                class="w-full border border-black/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                            <option value="active" @selected(old('status', $member->status) === 'active')>Aktif</option>
                            <option value="suspended" @selected(old('status', $member->status) === 'suspended')>Ditangguhkan</option>
                            <option value="blocked" @selected(old('status', $member->status) === 'blocked')>Diblokir</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="expired_at" class="block text-sm font-medium text-[#374151] mb-1.5">Berlaku Hingga</label>
                        <input id="expired_at" name="expired_at" type="date"
                               value="{{ old('expired_at', $member->expired_at?->format('Y-m-d')) }}"
                               class="w-full border border-black/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        <x-input-error :messages="$errors->get('expired_at')" class="mt-1.5" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-[#374151] mb-1.5">Alamat</label>
                        <textarea id="address" name="address" rows="2"
                                  class="w-full border border-black/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">{{ old('address', $member->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-1.5" />
                    </div>
                </div>

                <div class="bg-[#F9FAFB] rounded-xl p-4 flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-[#9CA3AF] mt-0.5"></i>
                    <p class="text-xs text-[#6B7280]">
                        Nomor Anggota (<strong>{{ $member->member_id ?? '-' }}</strong>) dan Role
                        (<strong>{{ ucfirst($member->role) }}</strong>) tidak bisa diubah dari halaman ini.
                        Untuk mengubah role, gunakan tombol "Ubah Role" di halaman Detail Anggota.
                    </p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.members.show', $member) }}"
                       class="px-5 py-2.5 rounded-xl text-sm text-[#6B7280] hover:bg-black/5 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-[#16331F] text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-[#1F4429] transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>