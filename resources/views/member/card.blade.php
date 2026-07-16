@extends('layouts.member')

@section('title', 'Kartu Anggota')

@section('content')

    <div class="max-w-lg mx-auto">

        {{-- ==================== HEADING ==================== --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl font-serif font-semibold text-stone-900">Kartu Keanggotaan Anda</h1>
            <p class="text-sm text-stone-500 mt-2">
                Tunjukkan kartu ini saat berkunjung ke perpustakaan, atau simpan di galeri HP Anda.
            </p>
        </div>

        {{-- ==================== KARTU ANGGOTA ==================== --}}
        <div id="printable-area">
            <div id="member-card" class="relative w-full aspect-[16/10] rounded-2xl overflow-hidden shadow-lg bg-[#1D3B2C] p-7 flex flex-col justify-between text-white">

                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/5"></div>
                <div class="absolute -right-4 top-16 w-24 h-24 rounded-full bg-white/5"></div>

                {{-- Header + Badge Status --}}
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="font-serif text-xl font-semibold tracking-wide">Lumina</p>
                        <p class="text-[10px] tracking-[0.2em] text-white/60 mt-0.5">KARTU ANGGOTA PERPUSTAKAAN</p>
                    </div>

                    <span @class([
                        'flex items-center gap-1.5 text-[10px] font-semibold tracking-wide px-3 py-1.5 rounded-full',
                        'bg-emerald-400/20 text-emerald-300' => $user->status === 'active',
                        'bg-amber-400/20 text-amber-300' => $user->status === 'suspended',
                        'bg-red-400/20 text-red-300' => $user->status === 'blocked',
                    ])>
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ match($user->status) {
                            'active' => 'AKTIF',
                            'suspended' => 'DITANGGUHKAN',
                            'blocked' => 'DIBLOKIR',
                            default => strtoupper($user->status),
                        } }}
                    </span>
                </div>

                {{-- Avatar Inisial + Nama --}}
                <div class="relative flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-[#E8B36A] flex items-center justify-center shrink-0">
                        <span class="text-2xl font-bold text-[#1D3B2C]">{{ $initials }}</span>
                    </div>
                    <div>
                        <p class="text-[10px] tracking-widest text-white/50">NAMA ANGGOTA</p>
                        <p class="text-lg font-semibold leading-tight">{{ $user->name }}</p>
                    </div>
                </div>

                {{-- Footer: ID & Masa Berlaku --}}
                <div class="relative flex items-end justify-between">
                    <div>
                        <p class="text-[10px] tracking-widest text-white/50">NOMOR ANGGOTA</p>
                        <p class="font-mono text-base tracking-wider mt-0.5">{{ $user->member_id ?? '—' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] tracking-widest text-white/50">BERLAKU HINGGA</p>
                        <p class="text-sm mt-0.5">
                            {{ $user->expired_at ? \Carbon\Carbon::parse($user->expired_at)->translatedFormat('d M Y') : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== TOMBOL AKSI ==================== --}}
        <div class="grid grid-cols-2 gap-3 mt-6 no-print">
            <button id="download-card-btn"
                    class="col-span-2 bg-[#1D3B2C] text-white text-sm font-semibold py-3.5 rounded-xl flex items-center justify-center gap-2 hover:bg-[#16301F] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span id="download-btn-text">Unduh sebagai Gambar</span>
            </button>

            <button id="print-card-btn"
                    class="bg-white border border-[#ECE7DC] text-stone-700 text-sm font-semibold py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-[#F3F1E9] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-8 4h8v-6H8v6z" />
                </svg>
                Cetak Kartu
            </button>

            <a href="{{ route('member.dashboard') }}"
               class="bg-white border border-[#ECE7DC] text-stone-700 text-sm font-semibold py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-[#F3F1E9] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- ==================== INFO BENEFIT KEANGGOTAAN ==================== --}}
        <div class="bg-white border border-[#ECE7DC] rounded-2xl p-5 mt-6 no-print">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-[#E9F3EC] flex items-center justify-center shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#1D3B2C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-stone-900 mb-2">Fasilitas Keanggotaan Anda</p>
                    <ul class="text-sm text-stone-600 space-y-1.5">
                        <li class="flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-[#1D3B2C]"></span>
                            Maksimal <strong>{{ $user->max_loans }} buku</strong> dipinjam bersamaan
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-[#1D3B2C]"></span>
                            Masa pinjam <strong>31 hari</strong>, bisa diperpanjang 1x (+31 hari)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-[#1D3B2C]"></span>
                            Denda keterlambatan <strong>Rp1.000/hari</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
    @media print {
        body * { visibility: hidden; }
        #printable-area, #printable-area * { visibility: visible; }
        #printable-area { position: absolute; top: 0; left: 0; width: 100%; }
        .no-print { display: none !important; }
       
        #member-card, #member-card * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
</style>
<script>
    document.getElementById('download-card-btn').addEventListener('click', function () {
        const btnText = document.getElementById('download-btn-text');
        const originalText = btnText.textContent;
        btnText.textContent = 'Memproses...';

        const cardElement = document.getElementById('member-card');

        html2canvas(cardElement, {
            scale: 3,
            backgroundColor: null,
            useCORS: true,
        }).then(function (canvas) {
            const link = document.createElement('a');
            link.download = 'kartu-anggota-lumina-{{ $user->member_id ?? "member" }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            btnText.textContent = originalText;
        }).catch(function (err) {
            console.error('Gagal membuat gambar kartu:', err);
            btnText.textContent = 'Gagal, coba lagi';
            setTimeout(() => btnText.textContent = originalText, 2000);
        });
    });

    document.getElementById('print-card-btn').addEventListener('click', function () {
        window.print();
    });
</script>
@endpush