<section class="py-20 px-6 lg:px-16 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14">
            <p class="text-[#E8B36A] text-xs font-semibold tracking-widest uppercase mb-3">Nilai Kami</p>
            <h2 class="font-display text-3xl font-semibold text-[#16331F]">Yang mendorong kami setiap hari</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['icon' => 'fa-book-open',   'judul' => 'Akses untuk Semua', 'desc' => 'Setiap warga berhak mendapatkan pengetahuan tanpa hambatan ekonomi atau sosial.'],
                ['icon' => 'fa-seedling',     'judul' => 'Tumbuh Bersama',    'desc' => 'Kami percaya komunitas yang membaca bersama akan tumbuh dan berkembang bersama.'],
                ['icon' => 'fa-shield-halved','judul' => 'Terpercaya',        'desc' => 'Koleksi kami dikurasi dengan teliti untuk memastikan kualitas dan kebenaran informasi.'],
            ] as $nilai)
                <div class="text-center p-8 rounded-2xl border border-black/5 hover:border-[#E8B36A]/30 transition">
                    <div class="w-14 h-14 rounded-2xl bg-[#16331F]/10 flex items-center justify-center mx-auto mb-5">
                        <i class="fa-solid {{ $nilai['icon'] }} text-[#16331F] text-xl"></i>
                    </div>
                    <h3 class="font-semibold text-[#1F2937] mb-3">{{ $nilai['judul'] }}</h3>
                    <p class="text-sm text-[#6B7280] leading-relaxed">{{ $nilai['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>