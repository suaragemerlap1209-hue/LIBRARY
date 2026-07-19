@extends('layouts.member')

@section('title', 'Katalog')
@section('page-title', 'Katalog')

@section('topbar-search')
    <form method="GET" action="{{ route('member.catalog.index') }}" class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, penulis..."
               class="w-72 pl-9 pr-4 py-2 rounded-full bg-white border border-stone-200 text-sm
                      placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/30">
    </form>
@endsection

@section('content')

    @php
        $activeCategory = request('category', 'Semua Koleksi');
        $allCategories = collect(['Semua Koleksi'])->merge($categories);
        $featured = $books->first();
        $gridBooks = $featured ? $books->skip(1) : $books;

        // Helper lokal: tentukan URL cover final untuk 1 buku
        $resolveCover = function ($book) {
            if (empty($book->cover)) {
                return 'https://placehold.co/300x400/2f4f3f/ffffff?text=' . urlencode($book->title);
            }
            if (str_starts_with($book->cover, 'http://') || str_starts_with($book->cover, 'https://')) {
                return $book->cover;
            }
            return asset('storage/' . $book->cover);
        };
    @endphp

    {{-- ===== FILTER PILLS ===== --}}
    <div class="flex flex-wrap items-center gap-2 mb-8">
        @foreach ($allCategories as $category)
            @php $isActive = $activeCategory === $category; @endphp
            <a href="{{ route('member.catalog.index', array_filter(['category' => $category, 'q' => request('q')])) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition
                      {{ $isActive ? 'bg-[#152B1E] text-white' : 'bg-[#DCEBD9] text-[#2F5233] hover:bg-[#cfe4ca]' }}">
                {{ $category }}
            </a>
        @endforeach

        <button type="button"
                class="ml-auto flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium border border-stone-300 text-stone-600 hover:bg-white">
            Filter
        </button>
    </div>

    @if ($books->isEmpty())
        <div class="bg-white border border-stone-100 rounded-2xl p-10 text-center text-stone-500">
            Belum ada buku yang cocok dengan pencarian/kategori ini.
        </div>
    @else
        {{-- ===== FEATURED BANNER ===== --}}
        @if ($featured)
            @php
                $featuredStock = $featured->stock ?? $featured->available_copies ?? 0;
                $featuredCover = $resolveCover($featured);
            @endphp
            <div class="bg-[#E7EFE2] rounded-2xl p-6 flex gap-6 items-stretch mb-6">
                <img src="{{ $featuredCover }}"
                     alt="{{ $featured->title }}"
                     width="160" height="200"
                     style="width:160px;height:200px;object-fit:cover;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.15);background:#eee;"
                     onerror="this.onerror=null;this.src='https://placehold.co/300x400/2f4f3f/ffffff?text={{ urlencode($featured->title) }}';">

                <div class="flex flex-col">
                    <span class="w-fit mb-3 inline-block text-xs font-medium px-3 py-1 rounded-full
                                 {{ $featuredStock > 0 ? 'bg-[#DCEBD9] text-[#2F5233]' : 'bg-stone-200 text-stone-600' }}">
                        {{ $featuredStock > 0 ? 'Available' : 'Borrowed' }}
                    </span>
                    <h3 class="text-xl font-semibold text-stone-900 mb-2">{{ $featured->title }}</h3>
                    <p class="text-sm text-stone-600 mb-4 leading-relaxed">
                        {{ \Illuminate\Support\Str::limit($featured->description, 160) }}
                    </p>
                    @if ($featured->isbn)
                        <p class="text-sm text-stone-500 mb-1">ISBN: {{ $featured->isbn }}</p>
                    @endif
                    <p class="text-sm font-medium text-[#2F5233] mb-4">{{ $featuredStock }} buku tersedia</p>

                    <form method="POST" action="{{ route('member.loans.store', $featured) }}" class="mt-auto">
                        @csrf
                        <button type="submit"
                                class="w-fit bg-[#152B1E] text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-[#1f3d29]"
                                {{ $featuredStock <= 0 ? 'disabled' : '' }}>
                            {{ $featuredStock > 0 ? 'Pinjam Sekarang' : 'Beri Tahu Saya' }}
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- ===== GRID BUKU LAINNYA (KODE INLINE, TIDAK PAKAI KOMPONEN) ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($gridBooks as $book)
                @php
                    $stock = $book->stock ?? $book->available_copies ?? 0;
                    $isAvailable = $stock > 0;
                    $cover = $resolveCover($book);
                @endphp

                <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-4 flex flex-col">
                    <a href="{{ route('member.catalog.show', $book) }}" class="relative mb-4 block">
                        <img src="{{ $cover }}"
                             alt="{{ $book->title }}"
                             width="400" height="224"
                             style="width:100%;height:224px;object-fit:cover;border-radius:12px;background:#eee;display:block;"
                             onerror="this.onerror=null;this.src='https://placehold.co/300x400/2f4f3f/ffffff?text={{ urlencode($book->title) }}';">
                        <span class="absolute top-3 left-3 inline-block text-xs font-medium px-3 py-1 rounded-full
                                     {{ $isAvailable ? 'bg-[#DCEBD9] text-[#2F5233]' : 'bg-stone-200 text-stone-600' }}">
                            {{ $isAvailable ? 'Available' : 'Borrowed' }}
                        </span>
                    </a>

                    <a href="{{ route('member.catalog.show', $book) }}">
                        <h3 class="text-base font-semibold text-stone-900 truncate hover:underline">{{ $book->title }}</h3>
                    </a>
                    <p class="text-sm text-stone-500 mb-4">{{ $book->author }}</p>

                    @if ($isAvailable)
                        <form method="POST" action="{{ route('member.loans.store', $book) }}" class="mt-auto">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-[#F3C89C] text-[#152B1E] text-sm font-semibold py-2.5 rounded-lg hover:bg-[#efb87e] transition">
                                Pinjam Sekarang
                            </button>
                        </form>
                    @else
                        <button type="button"
                                class="mt-auto w-full border border-stone-300 text-stone-700 text-sm font-semibold py-2.5 rounded-lg hover:bg-stone-50 transition">
                            Beri Tahu Saya
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

@endsection