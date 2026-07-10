<div class="sticky top-[72px] z-20 bg-[#FAF7F0]/95 backdrop-blur border-b border-black/5 px-6 lg:px-16 py-3">
    <div class="max-w-6xl mx-auto flex items-center gap-2 overflow-x-auto scrollbar-none">
        @foreach([
            ['label' => 'Panduan Pinjam',     'anchor' => 'panduan-pinjam'],
            ['label' => 'Kebijakan Denda',     'anchor' => 'kebijakan-denda'],
            ['label' => 'Syarat & Ketentuan',  'anchor' => 'syarat-ketentuan'],
            ['label' => 'FAQ',                 'anchor' => 'pusat-bantuan'],
            ['label' => 'Hubungi Kami',        'anchor' => 'hubungi'],
        ] as $nav)
            <a href="#{{ $nav['anchor'] }}"
               class="shrink-0 text-xs font-medium px-4 py-2 rounded-full border border-black/10
                      text-[#6B7280] hover:bg-[#16331F] hover:text-white hover:border-[#16331F] transition">
                {{ $nav['label'] }}
            </a>
        @endforeach
    </div>
</div>
