<section id="collection" class="py-24 px-8 lg:px-16 reveal">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-medium tracking-widest uppercase text-[#6B7564] mb-2">Koleksi Terkurasi</p>
                <h2 class="font-display text-3xl text-[#1A1A18]">Temukan Buku yang Tepat</h2>
            </div>
            <a href="{{ route('catalog.public') }}" class="text-sm text-[#16331F] font-medium hover:text-[#C9973A] transition flex items-center gap-1">
                Lihat Semua Kategori <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $categories = [
                    ['label' => 'Sains & Alam',  'count' => '1.200+ Buku', 'color' => '#2D5016',
                     'img' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=400&q=80'],
                    ['label' => 'Sejarah',        'count' => '890+ Buku',   'color' => '#5C3D1E',
                     'img' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&q=80'],
                    ['label' => 'Filsafat',       'count' => '640+ Buku',   'color' => '#3D4A2E',
                     'img' => 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?w=400&q=80'],
                    ['label' => 'Fiksi',          'count' => '1.500+ Buku', 'color' => '#1A3040',
                     'img' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=400&q=80'],
                ];
            @endphp

            @foreach($categories as $cat)
                <div class="cat-card relative rounded-2xl overflow-hidden aspect-[3/4] cursor-pointer group">
                    <img src="{{ $cat['img'] }}" alt="{{ $cat['label'] }}"
                         class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0" style="background: linear-gradient(to top, {{ $cat['color'] }}ee 0%, transparent 50%)"></div>
                    <div class="cat-overlay absolute inset-0 bg-[#16331F]/20"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <p class="text-[10px] text-white/60 mb-1">{{ $cat['count'] }}</p>
                        <p class="text-white font-medium text-sm">{{ $cat['label'] }}</p>
                        <button class="mt-3 w-full py-1.5 rounded-full border border-white/30 text-white text-xs hover:bg-white hover:text-[#1A1A18] transition">
                            Jelajahi
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>