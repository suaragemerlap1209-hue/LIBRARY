@extends('layouts.member')

@section('title', 'Pengaturan')

@section('content')

    <div class="max-w-2xl space-y-6">

        {{-- ==================== PROFIL ==================== --}}
        <div class="bg-white rounded-2xl border border-[#ECE7DC] p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- ==================== PASSWORD ==================== --}}
        <div class="bg-white rounded-2xl border border-[#ECE7DC] p-6">
            @include('profile.partials.update-password-form')
        </div>

        {{-- ==================== 2FA (UI Placeholder) ==================== --}}
        <div class="bg-white rounded-2xl border border-[#ECE7DC] p-6">
            <section>
                <header class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Autentikasi Dua Faktor</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Tambahkan lapisan keamanan ekstra pada akun Anda menggunakan aplikasi authenticator.
                        </p>
                    </div>
                    <span class="shrink-0 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 border border-amber-200">
                        Segera Hadir
                    </span>
                </header>

                <div class="mt-6 flex items-center justify-between opacity-50 pointer-events-none select-none">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Aplikasi Authenticator (TOTP)</p>
                        <p class="text-xs text-gray-500 mt-0.5">Gunakan Google Authenticator, Authy, atau aplikasi serupa.</p>
                    </div>

                    <button type="button" disabled
                            class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 cursor-not-allowed">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white translate-x-1 transition"></span>
                    </button>
                </div>

                <p class="mt-4 text-xs text-gray-400">
                    Fitur ini sedang dalam pengembangan dan akan segera tersedia.
                </p>
            </section>
        </div>

        {{-- ==================== HAPUS AKUN ==================== --}}
        <div class="bg-white rounded-2xl border border-[#ECE7DC] p-6">
            @include('profile.partials.delete-user-form')
        </div>

    </div>

@endsection