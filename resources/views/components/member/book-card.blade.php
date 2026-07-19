@props(['book'])

@php
    $isAvailable = $book->available_copies > 0;
    $status = $isAvailable ? 'available' : 'borrowed';
    $cover = $book->cover
        ? asset('storage/' . $book->cover)
        : 'https://placehold.co/300x360/2f4f3f/e8dcc4?text=' . urlencode($book->title);
@endphp

    <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-4 flex flex-col">
        <a href="{{ route('member.catalog.show', $book) }}">
            <img src="{{ $cover }}" alt="{{ $book->title }}"
                    class="w-full h-48 object-cover rounded-xl mb-3">
        </a>

        <a href="{{ route('member.catalog.show', $book) }}">
            <h3 class="text-base font-semibold text-stone-900 truncate hover:underline">{{ $book->title }}</h3>
        </a>
        
        <p class="text-sm text-stone-500 mb-3">{{ $book->author }}</p>

        <a href="{{ route('member.catalog.show', $book) }}"
            class="inline-flex items-center gap-1 text-sm font-medium text-[#2F5233] hover:underline mb-4">
                Lihat Detail
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </a>

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
                class="mt-auto w-full flex items-center justify-center gap-2 border border-stone-300 text-stone-700 text-sm font-semibold py-2.5 rounded-lg hover:bg-stone-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            Beri Tahu Saya Jika Tersedia
        </button>
    @endif
</div>