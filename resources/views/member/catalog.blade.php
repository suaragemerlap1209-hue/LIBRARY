<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-[#1F2937]">Katalog Buku (Testing)</h2>
    </x-slot>

    <div class="max-w-5xl">

        {{-- Pesan sukses/error --}}
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

        {{-- Form pencarian sederhana --}}
        <form method="GET" class="mb-6 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul atau penulis..."
                   class="border rounded-lg px-3 py-2 text-sm w-64">
            <select name="category_id" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Cari</button>
        </form>

        {{-- Daftar buku --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($books as $book)
                <div class="border rounded-xl p-4">
                    <h3 class="font-bold text-sm">{{ $book->title }}</h3>
                    <p class="text-xs text-gray-500 mb-1">{{ $book->author }}</p>
                    <p class="text-xs text-gray-400 mb-2">{{ $book->category->name ?? 'Tanpa kategori' }}</p>
                    <p class="text-xs mb-3">Stok: <span class="font-semibold">{{ $book->stock }}</span></p>

                    <a href="{{ route('member.catalog.show', $book) }}"
                       class="text-xs text-blue-600 underline">Lihat detail</a>
                </div>
            @empty
                <p class="text-sm text-gray-500 col-span-3">Belum ada buku.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $books->links() }}
        </div>
    </div>
</x-app-layout>