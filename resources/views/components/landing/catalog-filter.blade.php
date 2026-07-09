@props(['categories' => []])

<section class="px-6 lg:px-16 pt-6 pb-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-wrap gap-2 justify-center">
            <button onclick="filterCategory('all')" id="filter-all"
                    class="filter-btn px-4 py-1.5 rounded-full text-sm border transition bg-[#16331F] text-white border-[#16331F]">
                Semua
            </button>
            @foreach($categories as $cat)
                <button onclick="filterCategory('{{ Str::slug($cat) }}')" id="filter-{{ Str::slug($cat) }}"
                        class="filter-btn px-4 py-1.5 rounded-full text-sm border transition bg-white text-[#6B7280] border-black/10 hover:border-[#16331F]/30 hover:text-[#16331F]">
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    </div>
</section>