@props(['books' => collect()])

@php
    $dummyBooks = collect([
        (object)['title' => 'Filosofi Teras', 'author' => 'Henry Manampiring', 'category' => (object)['name' => 'Filsafat'], 'stock' => 3, 'cover' => null],
        (object)['title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'category' => (object)['name' => 'Fiksi'], 'stock' => 0, 'cover' => null],
        (object)['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'category' => (object)['name' => 'Sejarah'], 'stock' => 2, 'cover' => null],
        (object)['title' => 'Atomic Habits', 'author' => 'James Clear', 'category' => (object)['name' => 'Pendidikan'], 'stock' => 5, 'cover' => null],
        (object)['title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'category' => (object)['name' => 'Fiksi'], 'stock' => 1, 'cover' => null],
        (object)['title' => 'Homo Deus', 'author' => 'Yuval Noah Harari', 'category' => (object)['name' => 'Sains'], 'stock' => 0, 'cover' => null],
        (object)['title' => 'Negeri 5 Menara', 'author' => 'A. Fuadi', 'category' => (object)['name' => 'Fiksi'], 'stock' => 4, 'cover' => null],
        (object)['title' => 'Deep Work', 'author' => 'Cal Newport', 'category' => (object)['name' => 'Pendidikan'], 'stock' => 2, 'cover' => null],
    ]);

    $displayBooks = $books->isEmpty() ? $dummyBooks : $books;
@endphp

<<section class="px-6 lg:px-16 pt-6 pb-20">
    <div class="max-w-6xl mx-auto">

        <div id="books-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($displayBooks as $book)
                @php
                    $catName = $book->category->name ?? 'Umum';
                    $slug = Str::slug($catName);
                    // Pakai method model kalau ini instance Book asli, fallback ke cek stock manual kalau dummy
                    $tersedia = method_exists($book, 'isAvailable') ? $book->isAvailable() : ($book->stock ?? 0) > 0;
                @endphp
                <div class="book-card bg-white rounded-2xl border border-black/5 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all"
                     data-category="{{ $slug }}"
                     data-title="{{ strtolower($book->title) }}"
                     data-author="{{ strtolower($book->author) }}">

                    <div class="relative w-full h-48 bg-gradient-to-br from-[#16331F]/10 to-[#E8B36A]/10 flex items-center justify-center overflow-hidden">
                        @if(!empty($book->cover))
                            <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-book text-3xl text-[#16331F]/30"></i>
                        @endif

                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-medium
                                     {{ $tersedia ? 'bg-[#EAF3DE] text-[#3B6D11]' : 'bg-black/50 text-white' }}">
                            {{ $tersedia ? 'Tersedia' : 'Stok Habis' }}
                        </span>
                    </div>

                    <div class="p-4">
                        <span class="text-[10px] font-semibold text-[#B9882F] bg-[#E8B36A]/15 px-2 py-0.5 rounded-full">
                            {{ $catName }}
                        </span>
                        <h3 class="font-semibold text-sm text-[#1F2937] mt-2 mb-0.5 line-clamp-2">{{ $book->title }}</h3>
                        <p class="text-xs text-[#6B7280]">{{ $book->author }}</p>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ $book instanceof \App\Models\Book ? route('catalog.show', $book) : '#' }}"
                                class="flex-1 text-xs font-medium py-2 rounded-lg border border-[#16331F]/20 text-[#16331F] hover:bg-[#16331F] hover:text-white transition text-center">
                                Detail
                            </a>
                            @if($tersedia)
                                <a href="{{ route('register') }}"
                                   class="flex-1 text-xs font-medium py-2 rounded-lg bg-[#E8B36A] hover:bg-[#d9a25c] text-[#1F2937] text-center transition">
                                    Pinjam
                                </a>
                            @else
                                <button disabled
                                        class="flex-1 text-xs font-medium py-2 rounded-lg bg-black/5 text-[#9CA3AF] text-center cursor-not-allowed">
                                    Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="empty-state" class="hidden text-center py-20">
            <i class="fa-regular fa-folder-open text-4xl text-[#D1D5DB] mb-4"></i>
            <p class="text-[#9CA3AF] text-sm">Tidak ada buku ditemukan.</p>
        </div>

    </div>
</section>