<x-guest-layout :centered="true">
    <div class="mb-7">
        <h2 class="text-xl font-semibold text-[#1F2937] mb-1">Selamat Datang</h2>
        <p class="text-sm text-[#9CA3AF]">Silakan masuk ke akun keanggotaan Anda.</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-[#374151] mb-1.5">Email</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF]">
                    <i class="fa-regular fa-address-card text-sm"></i>
                </span>
                <input id="email" name="email" type="email" placeholder="nama@email.com"
                       value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full border border-black/10 rounded-xl pl-11 pr-4 py-3 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F] transition">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-[#374151] mb-1.5">Kata Sandi</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF]">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input :type="show ? 'text' : 'password'" id="password" name="password"
                       placeholder="Masukkan kata sandi"
                       required autocomplete="current-password"
                       class="w-full border border-black/10 rounded-xl pl-11 pr-11 py-3 text-sm text-[#1F2937] bg-white focus:outline-none focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F] transition">
                <button type="button" @click="show = !show"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF] hover:text-[#16331F]">
                    <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-black/20 text-[#16331F] focus:ring-[#16331F]/30">
                <span class="text-sm text-[#374151]">Ingat Saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-[#16331F] hover:underline">Lupa Sandi?</a>
            @endif
        </div>

        <button type="submit"
                class="w-full bg-[#E8B36A] hover:bg-[#d9a25c] text-[#1F2937] text-sm font-semibold py-3.5 rounded-xl transition flex items-center justify-center gap-2">
            Masuk <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <p class="text-center text-sm text-[#6B7280] mt-6">
        Belum menjadi anggota? <a href="{{ route('register') }}" class="text-[#1F2937] font-semibold hover:underline">Daftar Sekarang</a>
    </p>
</x-guest-layout>