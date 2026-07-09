<x-landing.landing-layout title="Katalog Buku — Lumina Library">

    <x-landing.catalog-hero />
    <x-landing.catalog-filter :categories="$categories" />
    <x-landing.catalog-grid :books="$books" />
    <x-landing.catalog-cta />

    @push('scripts')
    <script>
        let activeCategory = 'all';
        let searchQuery = '';

        function filterCategory(cat) {
            activeCategory = cat;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-[#16331F]', 'text-white', 'border-[#16331F]');
                btn.classList.add('bg-white', 'text-[#6B7280]', 'border-black/10');
            });
            const activeBtn = document.getElementById('filter-' + cat);
            if (activeBtn) {
                activeBtn.classList.add('bg-[#16331F]', 'text-white', 'border-[#16331F]');
                activeBtn.classList.remove('bg-white', 'text-[#6B7280]', 'border-black/10');
            }
            applyFilter();
        }

        function applyFilter() {
            const cards = document.querySelectorAll('.book-card');
            let visible = 0;
            cards.forEach(card => {
                const matchCat = activeCategory === 'all' || card.dataset.category === activeCategory;
                const matchSearch = searchQuery === '' ||
                    card.dataset.title.includes(searchQuery) ||
                    card.dataset.author.includes(searchQuery);
                const show = matchCat && matchSearch;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            document.getElementById('empty-state').classList.toggle('hidden', visible > 0);
        }

        document.getElementById('search-input')?.addEventListener('input', function () {
            searchQuery = this.value.toLowerCase();
            applyFilter();
        });
    </script>
    @endpush

</x-landing.landing-layout>