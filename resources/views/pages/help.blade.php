<x-landing.landing-layout title="Pusat Bantuan — Lumina Library">

    <style>
        .faq-body { max-height: 0; overflow: hidden; transition: max-height .35s ease, padding .35s ease; }
        .faq-body.open { max-height: 500px; }
        .faq-icon { transition: transform .3s ease; }
        .faq-icon.open { transform: rotate(45deg); }
    </style>

    <x-landing.help-hero />
    <x-landing.help-nav />

    <div class="max-w-6xl mx-auto px-6 lg:px-16 py-16 space-y-24">
        <x-landing.help-panduan-pinjam />
        <hr class="border-black/5">
        <x-landing.help-kebijakan-denda />
        <hr class="border-black/5">
        <x-landing.help-syarat-ketentuan />
        <hr class="border-black/5">
        <x-landing.help-faq />
        <hr class="border-black/5">
        <x-landing.help-kontak />
    </div>

    @push('scripts')
    <script>
        function toggleFaq(i) {
            const body = document.getElementById('faq-body-' + i);
            const icon = document.getElementById('faq-icon-' + i);
            const isOpen = body.classList.contains('open');

            document.querySelectorAll('.faq-body').forEach(el => el.classList.remove('open'));
            document.querySelectorAll('.faq-icon').forEach(el => el.classList.remove('open'));

            if (!isOpen) {
                body.classList.add('open');
                icon.classList.add('open');
            }
        }

        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
    @endpush

</x-landing.landing-layout>
