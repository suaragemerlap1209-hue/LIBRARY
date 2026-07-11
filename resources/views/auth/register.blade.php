<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-[#1F2937] mb-1">Pendaftaran Anggota</h2>
        <p class="text-sm text-[#9CA3AF]">Mulai perjalanan literasi Anda hari ini.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-[#374151] mb-1.5">Nama Lengkap</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF]">
                    <i class="fa-regular fa-user text-sm"></i>
                </span>
                <input id="name" name="name" type="text" placeholder="Masukkan nama sesuai KTP"
                       value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full border border-black/10 rounded-xl pl-11 pr-4 py-3 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F] transition">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-[#374151] mb-1.5">Email</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF]">
                    <i class="fa-regular fa-envelope text-sm"></i>
                </span>
                <input id="email" name="email" type="email" placeholder="nama@email.com"
                       value="{{ old('email') }}" required autocomplete="username"
                       class="w-full border border-black/10 rounded-xl pl-11 pr-4 py-3 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F] transition">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <label for="birth_date" class="block text-sm font-medium text-[#374151] mb-1.5">
                Tanggal Lahir <span class="text-[#A32D2D]">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF]">
                    <i class="fa-regular fa-calendar text-sm"></i>
                </span>
                <input id="birth_date" name="birth_date" type="date"
                       value="{{ old('birth_date') }}" required
                       max="{{ now()->subYears(13)->format('Y-m-d') }}"
                       class="w-full border border-black/10 rounded-xl pl-11 pr-4 py-3 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F] transition">
            </div>
            <x-input-error :messages="$errors->get('birth_date')" class="mt-1.5" />
            <p class="text-xs text-[#9CA3AF] mt-1">Menentukan batas jumlah pinjaman. Minimal usia 13 tahun.</p>
        </div>

        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-[#374151] mb-1.5">Password</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF]">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input :type="show ? 'text' : 'password'" id="password" name="password"
                       required autocomplete="new-password"
                       class="w-full border border-black/10 rounded-xl pl-11 pr-11 py-3 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F] transition">
                <button type="button" @click="show = !show"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF] hover:text-[#16331F]">
                    <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div x-data="{ show: false }">
            <label for="password_confirmation" class="block text-sm font-medium text-[#374151] mb-1.5">Konfirmasi Password</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF]">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                       required autocomplete="new-password"
                       class="w-full border border-black/10 rounded-xl pl-11 pr-11 py-3 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F] transition">
                <button type="button" @click="show = !show"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF] hover:text-[#16331F]">
                    <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit"
                class="w-full bg-[#E8B36A] hover:bg-[#d9a25c] text-[#1F2937] text-sm font-semibold py-3.5 rounded-xl transition">
            Daftar Sekarang
        </button>
    </form>

    <p class="text-center text-sm text-[#6B7280] mt-6">
        Sudah memiliki akun? <a href="{{ route('login') }}" class="text-[#1F2937] font-semibold hover:underline">Masuk</a>
    </p>
</x-guest-layout>