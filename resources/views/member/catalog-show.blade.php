@extends('layouts.member')

@section('title', $book->title)
@section('page-title', 'Book Detail')

@section('content')

    @php
        $isAvailable = $book->available_copies > 0;
        $cover = $book->cover
            ? asset('storage/' . $book->cover)
            : 'https://placehold.co/400x520/2f4f3f/e8dcc4?text=' . urlencode($book->title);
    @endphp

    {{-- ===== BACK LINK ===== --}}
    <a href="{{ route('member.catalog.index') }}"
       class="inline-flex items-center gap-2 text-sm text-stone-500 hover:text-stone-800 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Back to Catalog
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ===== COVER ===== --}}
        <div class="lg:col-span-1">
            <img src="{{ $cover }}" alt="{{ $book->title }}"
                 class="w-full rounded-2xl shadow-md object-cover aspect-[3/4]">
        </div>

        {{-- ===== DETAIL ===== --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-100 shadow-sm p-8">
            <x-badge-status :status="$isAvailable ? 'available' : 'borrowed'" class="mb-4" />

            <h1 class="text-2xl font-semibold text-stone-900 mb-1">{{ $book->title }}</h1>
            <p class="text-stone-500 mb-1">by {{ $book->author }}</p>
            @if ($book->category)
                <p class="text-sm text-[#2F5233] font-medium mb-6">{{ $book->category->name }}</p>
            @else
                <div class="mb-6"></div>
            @endif

            @if ($book->description)
                <p class="text-stone-600 leading-relaxed mb-6">{{ $book->description }}</p>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
                @if ($book->isbn)
                    <div>
                        <p class="text-xs text-stone-400 uppercase tracking-wide">ISBN</p>
                        <p class="text-sm font-medium text-stone-800">{{ $book->isbn }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wide">Copies Available</p>
                    <p class="text-sm font-medium text-stone-800">{{ $book->available_copies }}</p>
                </div>
                <div>
                    <p class="text-xs text-stone-400 uppercase tracking-wide">Loan Period</p>
                    <p class="text-sm font-medium text-stone-800">14 Days</p>
                </div>
            </div>

            @if ($isAvailable)
                <form method="POST" action="{{ route('member.loans.store', $book) }}">
                    @csrf
                    <button type="submit"
                            class="bg-[#F3C89C] text-[#152B1E] text-sm font-semibold px-6 py-3 rounded-lg hover:bg-[#efb87e] transition">
                        Borrow Now
                    </button>
                </form>
            @else
                <button type="button"
                        class="flex items-center gap-2 border border-stone-300 text-stone-700 text-sm font-semibold px-6 py-3 rounded-lg hover:bg-stone-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    Notify Me When Available
                </button>
            @endif
        </div>
    </div>

@endsection