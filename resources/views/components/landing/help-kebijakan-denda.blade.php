<section id="kebijakan-denda" class="scroll-mt-36">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-xl bg-[#A32D2D]/10 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-triangle-exclamation text-[#A32D2D]"></i>
        </div>
        <div>
            <p class="text-[11px] text-[#9CA3AF] uppercase tracking-widest">Kebijakan</p>
            <h2 class="font-display text-2xl font-semibold text-[#1F2937]">Kebijakan Denda</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <h3 class="font-semibold text-sm text-[#1F2937] mb-4 flex items-center gap-2">
                <i class="fa-solid fa-calculator text-[#A32D2D] text-xs"></i> Perhitungan Denda
            </h3>
            <div class="space-y-3">
                @foreach([
                    ['label' => 'Tarif denda',        'val' => 'Rp1.000 / hari / buku'],
                    ['label' => 'Mulai dihitung',     'val' => 'Setelah jatuh tempo (H+1)'],
                    ['label' => 'Dihitung otomatis',  'val' => 'Setiap hari oleh sistem'],
                    ['label' => 'Maks. pinjam',       'val' => '14 hari sejak tanggal pinjam'],
                ] as $row)
                    <div class="flex items-center justify-between py-2 border-b border-black/5 last:border-0">
                        <span class="text-xs text-[#6B7280]">{{ $row['label'] }}</span>
                        <span class="text-xs font-semibold text-[#1F2937]">{{ $row['val'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <h3 class="font-semibold text-sm text-[#1F2937] mb-4 flex items-center gap-2">
                <i class="fa-solid fa-qrcode text-[#16331F] text-xs"></i> Cara Membayar Denda
            </h3>
            <ol class="space-y-3">
                @foreach([
                    'Login ke akun Lumina Library kamu.',
                    'Buka halaman Pembayaran Denda.',
                    'Scan QRIS.',
                    'Upload bukti pembayaran (foto/screenshot).',
                    'Tunggu konfirmasi dari admin (maks. 1×24 jam kerja).',
                    'Status denda berubah menjadi Lunas secara otomatis.',
                    'Atau anggota dapat langsung kunjungi petugas perpustakaan untuk membayar denda secara langsung.',
                ] as $i => $step)
                    <li class="flex gap-3 text-xs text-[#6B7280]">
                        <span class="w-5 h-5 rounded-full bg-[#16331F]/10 text-[#16331F] font-bold text-[10px] flex items-center justify-center shrink-0">{{ $i + 1 }}</span>
                        {{ $step }}
                    </li>
                @endforeach
            </ol>
        </div>
    </div>

    <div class="bg-[#FCEBEB] border border-[#F09595]/40 rounded-xl px-5 py-4 flex items-start gap-3">
        <i class="fa-solid fa-triangle-exclamation text-[#A32D2D] mt-0.5"></i>
        <p class="text-sm text-[#A32D2D] leading-relaxed">
            Akun dengan denda yang belum dibayar <strong>tidak dapat meminjam buku baru</strong> hingga seluruh tunggakan diselesaikan.
        </p>
    </div>
</section>
