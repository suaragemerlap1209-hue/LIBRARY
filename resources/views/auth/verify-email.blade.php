<x-guest-layout :centered="true">

    {{-- Icon --}}
    <div class="flex justify-center mb-6">
        <div class="w-14 h-14 rounded-2xl bg-[#16331F]/5 flex items-center justify-center">
            <i class="fa-solid fa-envelope-circle-check text-[#16331F] text-2xl"></i>
        </div>
    </div>

    {{-- Heading --}}
    <h2 class="font-display text-2xl font-semibold text-[#16331F] text-center mb-2">
        Verifikasi Email Kamu
    </h2>
    <p class="text-sm text-[#6B7280] text-center mb-6 leading-relaxed">
        Kami telah mengirimkan link verifikasi ke alamat email yang kamu daftarkan.
        Cek inbox atau folder <span class="font-medium text-[#1F2937]">spam</span>-mu.
    </p>

    {{-- Success alert --}}
    @if (session('status') == 'verification-link-sent')
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-6">
            <i class="fa-solid fa-circle-check text-green-500 flex-shrink-0"></i>
            Link verifikasi baru telah dikirim ke email kamu.
        </div>
    @endif

    {{-- Divider --}}
    <div class="border-t border-black/5 mb-6"></div>

    {{-- Email info --}}
    <div class="flex items-center gap-3 bg-[#FAF7F0] rounded-xl px-4 py-3 mb-6">
        <i class="fa-solid fa-user text-[#9CA3AF] text-sm flex-shrink-0"></i>
        <div class="min-w-0">
            <p class="text-xs text-[#9CA3AF]">Dikirim ke</p>
            <p class="text-sm font-medium text-[#1F2937] truncate">{{ auth()->user()->email }}</p>
        </div>
    </div>

    {{-- Resend button --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
            class="w-full bg-[#16331F] hover:bg-[#1e4429] text-white text-sm font-medium py-3 px-4 rounded-xl transition duration-150 flex items-center justify-center gap-2">
            <i class="fa-solid fa-paper-plane text-xs"></i>
            Kirim Ulang Email Verifikasi
        </button>
    </form>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm text-[#9CA3AF] hover:text-[#16331F] transition underline underline-offset-2">
            Keluar dari akun
        </button>
    </form>

    {{-- Help text --}}
    <p class="text-xs text-[#9CA3AF] text-center mt-6 leading-relaxed">
        Butuh bantuan?
        <a href="{{ route('bantuan') }}" class="text-[#16331F] hover:underline">Kunjungi halaman bantuan</a>
    </p>

</x-guest-layout>