<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('admin.books.index') }}" class="inline-flex items-center gap-1.5 text-xs text-[#9CA3AF] hover:text-[#16331F] transition mb-2">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali
        </a>
        <h2 class="text-xl font-bold text-[#1F2937]">
            {{ isset($book) ? 'Edit Buku' : 'Tambah Buku' }}
        </h2>
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST"
                  action="{{ isset($book) ? route('admin.books.update', $book) : route('admin.books.store') }}"
                  enctype="multipart/form-data" class="space-y-5">
                @csrf
                @if(isset($book))
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-1.5">Judul Buku</label>
                    <input type="text" name="title" value="{{ old('title', $book->title ?? '') }}"
                           placeholder="Masukkan judul buku"
                           class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                    @error('title')
                        <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">Penulis</label>
                        <input type="text" name="author" value="{{ old('author', $book->author ?? '') }}"
                               placeholder="Nama penulis"
                               class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        @error('author')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}"
                               placeholder="Opsional"
                               class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        @error('isbn')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">Kategori</label>
                        <select name="category_id" class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                            <option value="">Pilih kategori</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id', $book->category_id ?? '') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-1.5">Stok</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', $book->stock ?? 0) }}"
                               class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">
                        @error('stock')
                            <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4" placeholder="Opsional"
                              class="w-full rounded-xl border-black/10 text-sm focus:ring-2 focus:ring-[#16331F]/20 focus:border-[#16331F]">{{ old('description', $book->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-1.5">Sampul Buku</label>

                    @if(isset($book) && $book->cover)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover saat ini"
                                 class="w-16 h-24 rounded-lg object-cover border border-black/5">
                        </div>
                    @endif

                    <input type="file" name="cover" accept="image/*"
                           class="w-full text-sm text-[#6B7280] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-[#16331F]/10 file:text-[#16331F] file:text-sm file:font-medium hover:file:bg-[#16331F]/20">
                    <p class="text-xs text-[#9CA3AF] mt-1">Format JPG/PNG, maksimal 2MB.</p>
                    @error('cover')
                        <p class="text-xs text-[#A32D2D] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('admin.books.index') }}"
                       class="px-4 py-2.5 rounded-xl text-sm text-[#6B7280] hover:bg-black/5 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-sm bg-[#16331F] text-white font-medium hover:bg-[#1F4429] transition">
                        {{ isset($book) ? 'Simpan Perubahan' : 'Tambah Buku' }}
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>