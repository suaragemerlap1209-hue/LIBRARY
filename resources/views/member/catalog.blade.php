@extends('layouts.member')

@section('title', 'Catalog')
@section('page-title', 'Catalog')

@section('topbar-search')
    <form method="GET" action="{{ route('member.catalog.index') }}" class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title, author..."
               class="w-72 pl-9 pr-4 py-2 rounded-full bg-white border border-stone-200 text-sm
                      placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/30">
    </form>
@endsection

@section('content')

    @php
        // $books dan $categories dikirim dari Member\CatalogController@index
        $activeCategory = request('category', 'All Collections');
        $allCategories = collect(['All Collections'])->merge($categories);

        // Buku pertama dipakai sebagai featured banner (kalau ada)
        $featured = $books->first();
        $gridBooks = $featured ? $books->skip(1) : $books;
    @endphp

    {{-- ===== FILTER PILLS ===== --}}
    <div class="flex flex-wrap items-center gap-2 mb-8">
        @foreach ($allCategories as $category)
            @php $isActive = $activeCategory === $category; @endphp
            <a href="{{ route('member.catalog.index', array_filter(['category' => $category, 'q' => request('q')])) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition
                      {{ $isActive
                            ? 'bg-[#152B1E] text-white'
                            : 'bg-[#DCEBD9] text-[#2F5233] hover:bg-[#cfe4ca]' }}">
                {{ $category }}
            </a>
        @endforeach

        <button type="button"
                class="ml-auto flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium
                       border border-stone-300 text-stone-600 hover:bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>
            Filters
        </button>
    </div>

    @if ($books->isEmpty())
        <div class="bg-white border border-stone-100 rounded-2xl p-10 text-center text-stone-500">
            Belum ada buku yang cocok dengan pencarian/kategori ini.
        </div>
    @else
        {{-- ===== FEATURED BANNER ===== --}}
        @if ($featured)
            <div class="bg-[#E7EFE2] rounded-2xl p-6 flex gap-6 items-stretch mb-6">
                <img src="{{ $featured->cover_image ? asset('storage/' . $featured->cover_image) : 'https://placehold.co/220x280/1d3b2a/e8dcc4?text=' . urlencode($featured->title) }}"
                     alt="{{ $featured->title }}"
                     class="w-40 rounded-xl object-cover shadow-md">

                <div class="flex flex-col">
                    <x-badge-status :status="$featured->available_copies > 0 ? 'available' : 'borrowed'" class="w-fit mb-3" />
                    <h3 class="text-xl font-semibold text-stone-900 mb-2">{{ $featured->title }}</h3>
                    <p class="text-sm text-stone-600 mb-4 leading-relaxed">
                        {{ \Illuminate\Support\Str::limit($featured->description, 160) }}
                    </p>
                    @if ($featured->isbn)
                        <p class="text-sm text-stone-500 mb-1">ISBN: {{ $featured->isbn }}</p>
                    @endif
                    <p class="text-sm font-medium text-[#2F5233] mb-4">
                        {{ $featured->available_copies }} kopi tersedia
                    </p>

                    <form method="POST" action="{{ route('member.loans.store', $featured) }}" class="mt-auto">
                        @csrf
                        <button type="submit"
                                class="w-fit bg-[#152B1E] text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-[#1f3d29]"
                                {{ $featured->available_copies <= 0 ? 'disabled' : '' }}>
                            {{ $featured->available_copies > 0 ? 'Borrow Now' : 'Notify Me' }}
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- ===== GRID BUKU LAINNYA ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($gridBooks as $book)
                <x-member.book-card :book="$book" />
            @endforeach
        </div>
    @endif

@endsection