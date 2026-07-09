<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('member.catalog.index') }}" class="text-xs text-gray-500 mb-2 inline-block">
            &larr; Kembali ke Katalog
        </a>
        <h2 class="text-xl font-bold text-[#1F2937]">{{ $book->title }}</h2>
    </x-slot>

    <div class="max-w-2xl">

        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="border rounded-xl p-6">
            <p class="text-sm text-gray-500 mb-1">Penulis: {{ $book->author }}</p>
            <p class="text-sm text-gray-500 mb-1">Kategori: {{ $book->category->name ?? '-' }}</p>
            <p class="text-sm text-gray-500 mb-1">ISBN: {{ $book->isbn ?? '-' }}</p>
            <p class="text-sm text-gray-500 mb-3">Stok tersedia: <span class="font-semibold">{{ $book->stock }}</span></p>

            <p class="text-sm mb-4">{{ $book->description ?? 'Tidak ada deskripsi.' }}</p>

            <form method="POST" action="{{ route('member.loans.store', $book) }}">
                @csrf
                <button type="submit"
                        class="bg-[#16331F] text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-[#1F4429] transition"
                        @disabled($book->stock < 1)>
                    {{ $book->stock < 1 ? 'Stok Habis' : 'Pinjam Buku Ini' }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>