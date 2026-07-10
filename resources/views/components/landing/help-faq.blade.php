<section id="pusat-bantuan" class="scroll-mt-36">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-xl bg-[#2563EB]/10 flex items-center justify-center shrink-0">
            <i class="fa-regular fa-circle-question text-[#2563EB]"></i>
        </div>
        <div>
            <p class="text-[11px] text-[#9CA3AF] uppercase tracking-widest">FAQ</p>
            <h2 class="font-display text-2xl font-semibold text-[#1F2937]">Pertanyaan yang Sering Ditanyakan</h2>
        </div>
    </div>

    <div class="space-y-3">
        @foreach([
            ['q' => 'Apakah pendaftaran akun Lumina Library gratis?',
             'a' => 'Ya, pendaftaran akun Lumina Library sepenuhnya gratis. Tidak ada biaya pendaftaran maupun biaya langganan bulanan. Anda hanya dikenakan denda jika terlambat mengembalikan buku.'],
            ['q' => 'Berapa banyak buku yang bisa saya pinjam sekaligus?',
             'a' => 'Setiap anggota dapat meminjam buku sesuai dengan kategori umur anggota terdaftar (masih di pikirkan berapa jumlah buku yang akan di berikan) secara bersamaan. Untuk meminjam buku baru, minimal satu buku yang sedang dipinjam harus dikembalikan terlebih dahulu.'],
            ['q' => 'Berapa lama masa peminjaman buku?',
             'a' => 'Masa peminjaman standar adalah 30 hari kalender terhitung sejak tanggal peminjaman. Sistem akan otomatis mengirimkan notifikasi pengingat melalui email 1 hari sebelum jatuh tempo.'],
            ['q' => 'Bagaimana cara mengetahui tanggal jatuh tempo peminjaman saya?',
             'a' => 'Anda dapat melihat tanggal jatuh tempo di halaman "Peminjaman Saya" setelah login. Informasi ini juga tersedia di Kartu Anggota Digital Anda.'],
            ['q' => 'Apakah saya bisa memperpanjang masa peminjaman?',
             'a' => 'Saat ini fitur perpanjangan peminjaman belum tersedia. Anda harus mengembalikan buku terlebih dahulu sebelum dapat meminjamnya kembali.'],
            ['q' => 'Bagaimana cara membayar denda keterlambatan?',
             'a' => 'Denda dapat dibayar melalui QRIS atau langsung di loket perpustakaan. Setelah membayar, upload bukti pembayaran di halaman "Pembayaran Denda". Admin akan memverifikasi dalam 1×24 jam kerja dan status denda akan berubah menjadi Lunas.'],
            ['q' => 'Apa yang terjadi jika saya menghilangkan atau merusak buku?',
             'a' => 'Buku yang hilang atau rusak parah wajib diganti dengan buku yang sama atau membayar biaya penggantian sesuai harga buku. Silakan hubungi petugas perpustakaan untuk proses penggantian.'],
            ['q' => 'Bagaimana cara menghapus akun saya?',
             'a' => 'Anda dapat menghapus akun melalui halaman Pengaturan > Zona Bahaya > Hapus Akun. Pastikan tidak ada buku yang sedang dipinjam atau denda yang belum dibayar sebelum menghapus akun.'],
        ] as $i => $faq)
            <div class="bg-white rounded-xl border border-black/5 overflow-hidden">
                <button onclick="toggleFaq({{ $i }})"
                        class="w-full flex items-center justify-between px-5 py-4 text-left">
                    <span class="text-sm font-medium text-[#1F2937] pr-4">{{ $faq['q'] }}</span>
                    <i id="faq-icon-{{ $i }}" class="faq-icon fa-solid fa-plus text-[#9CA3AF] text-xs shrink-0"></i>
                </button>
                <div id="faq-body-{{ $i }}" class="faq-body">
                    <p class="px-5 pb-4 text-sm text-[#6B7280] leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
