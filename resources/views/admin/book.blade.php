<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-widest mb-0.5">Manajemen</p>
            <h2 class="text-xl font-bold text-[#1F2937]">Kelola Buku</h2>
        </div>
    </x-slot>

    <x-table-wrapper :is-empty="empty($books ?? [])" empty-text="Belum ada data buku.">
        <x-slot name="filters">
            <form method="GET" class="flex gap-2 flex-1 max-w-lg min-w-[280px]">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9CA3AF] text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul, penulis, atau ISBN..."
                           class="w-full pl-9 rounded-xl border-black/10 text-sm text-[#1F2937] placeholder:text-[#9CA3AF] focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                </div>
                <select name="category" class="rounded-xl border-black/10 text-sm text-[#374151] focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-black/5 text-[#374151] text-sm font-medium px-4 py-2 rounded-xl hover:bg-black/10 transition">
                    Filter
                </button>
            </form>

            <a href="{{ route('admin.books.create') }}"
               class="bg-[#16331F] text-white text-sm font-medium px-4 py-2 rounded-xl hover:bg-[#1F4429] transition inline-flex items-center gap-2 shrink-0">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Buku
            </a>
        </x-slot>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-black/5">
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Buku</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Penulis</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">ISBN</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Kategori</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide">Stok</th>
                    <th class="pb-3 text-[11px] font-semibold text-[#9CA3AF] uppercase tracking-wide text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @foreach($books as $book)
                    <tr class="hover:bg-black/[0.01] transition">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}"
                                         class="w-9 h-12 rounded-md object-cover border border-black/5 shrink-0">
                                @else
                                    <div class="w-9 h-12 rounded-md bg-[#16331F]/10 text-[#16331F] flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-book text-xs"></i>
                                    </div>
                                @endif
                                <span class="font-medium text-[#1F2937]">{{ $book->title }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-[#6B7280]">{{ $book->author }}</td>
                        <td class="py-4 text-[#6B7280]">{{ $book->isbn ?? '-' }}</td>
                        <td class="py-4 text-[#6B7280]">{{ $book->category->name ?? '-' }}</td>
                        <td class="py-4">
                            @if($book->stock > 0)
                                <span class="text-[#3B6D11] font-medium">{{ $book->stock }} buku</span>
                            @else
                                <span class="text-[#A32D2D] font-medium">Habis</span>
                            @endif
                        </td>
                        <td class="py-4 text-right">
                            <a href="{{ route('admin.books.edit', $book) }}" class="text-[#B9882F] font-semibold hover:underline text-xs mr-3">Edit</a>
                            <button type="button"
                                    x-data="{}"
                                    x-on:click="$dispatch('open-modal', 'delete-book-{{ $book->id }}')"
                                    class="text-[#A32D2D] font-semibold hover:underline text-xs">
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <x-modal name="delete-book-{{ $book->id }}" :show="false" max-width="sm">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-[#1F2937] mb-2">Hapus Buku?</h3>
                            <p class="text-xs text-[#6B7280] mb-5">
                                Buku "{{ $book->title }}" akan dihapus permanen dan tidak bisa dikembalikan.
                            </p>
                            <form method="POST" action="{{ route('admin.books.destroy', $book) }}" class="flex justify-end gap-2">
                                @csrf
                                @method('DELETE')
                                <button type="button" x-data="{}" x-on:click="$dispatch('close-modal', 'delete-book-{{ $book->id }}')"
                                        class="px-4 py-2 rounded-xl text-sm text-[#6B7280] hover:bg-black/5">Batal</button>
                                <button type="submit" class="px-4 py-2 rounded-xl text-sm bg-[#A32D2D] text-white hover:bg-[#8a2424]">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </x-modal>
                @endforeach
            </tbody>
        </table>

        @if(isset($books) && method_exists($books, 'links'))
            <x-slot name="footer">
                {{ $books->links() }}
            </x-slot>
        @endif
    </x-table-wrapper>
</x-app-layout>