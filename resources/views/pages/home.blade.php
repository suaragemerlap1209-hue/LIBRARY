<x-layout.landing-layout title="Lumina Library — Perpustakaan Digital Makassar">

    @push('styles')
        .float-card { animation: floatY 4s ease-in-out infinite; }
        @keyframes floatY { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .cat-card:hover .cat-overlay { opacity:1; }
        .cat-overlay { opacity:0; transition: opacity .3s; }
        .reveal { opacity:0; transform:translateY(24px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.visible { opacity:1; transform:none; }
    @endpush

    <x-landing.hero />
    <x-landing.about />
    <x-landing.collection />
    <x-landing.keunggulan />
    <x-landing.cara-bergabung />
    <x-landing.cta />

    @push('scripts')
    <script>
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => observer.observe(el));
    </script>
    @endpush

</x-landing-layout>