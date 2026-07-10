<x-landing.landing-layout title="{{ $book->title }} — Lumina Library">

    <section class="px-6 lg:px-16 pt-32 pb-20">
        <div class="max-w-5xl mx-auto">

            <a href="{{ route('catalog.public') }}" class="text-xs text-[#6B7280] hover:text-[#16331F] transition inline-flex items-center gap-1 mb-6">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Katalog
            </a>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <div class="md:col-span-1">
                    <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden bg-gradient-to-br from-[#16331F]/10 to-[#E8B36A]/10 flex items-center justify-center">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-book text-5xl text-[#16331F]/30"></i>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2">
                    <span class="text-[11px] font-semibold text-[#B9882F] bg-[#E8B36A]/15 px-3 py-1 rounded-full">
                        {{ $book->category->name ?? 'Umum' }}
                    </span>

                    <h1 class="text-2xl lg:text-3xl font-bold text-[#1F2937] mt-4 mb-1">{{ $book->title }}</h1>
                    <p class="text-sm text-[#6B7280] mb-6">oleh {{ $book->author }}</p>

                    <div class="flex items-center gap-3 mb-6">
                        @if($book->isAvailable())
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-[#EAF3DE] text-[#3B6D11]">
                                Tersedia — {{ $book->available_copies }} buku
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-black/50 text-white">
                                Stok Habis
                            </span>
                        @endif

                        @if($book->isbn)
                            <span class="text-xs text-[#9CA3AF]">ISBN: {{ $book->isbn }}</span>
                        @endif
                    </div>

                    <p class="text-sm text-[#374151] leading-relaxed mb-8">
                        {{ $book->description ?? 'Belum ada deskripsi untuk buku ini.' }}
                    </p>

                    <div class="flex gap-3">
                        @if($book->isAvailable())
                            <a href="{{ route('register') }}"
                               class="px-6 py-3 rounded-xl bg-[#E8B36A] hover:bg-[#d9a25c] text-[#1F2937] text-sm font-medium transition">
                                Daftar untuk Meminjam
                            </a>
                        @else
                            <button disabled class="px-6 py-3 rounded-xl bg-black/5 text-[#9CA3AF] text-sm font-medium cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                        <a href="{{ route('login') }}"
                           class="px-6 py-3 rounded-xl border border-[#16331F]/20 text-[#16331F] hover:bg-[#16331F] hover:text-white text-sm font-medium transition">
                            Sudah Punya Akun? Masuk
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

</x-landing.landing-layout>