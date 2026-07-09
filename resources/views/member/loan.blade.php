<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-[#1F2937]">Riwayat Peminjaman Saya (Testing)</h2>
    </x-slot>

    <div class="max-w-4xl">

        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full text-sm border rounded-xl overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Judul Buku</th>
                    <th class="p-3 text-left">Tgl Pinjam</th>
                    <th class="p-3 text-left">Jatuh Tempo</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    <tr class="border-t">
                        <td class="p-3">{{ $loan->book->title }}</td>
                        <td class="p-3">{{ $loan->borrowed_at->format('d M Y') }}</td>
                        <td class="p-3">{{ $loan->due_at->format('d M Y') }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100">{{ $loan->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-3 text-center text-gray-500">Belum ada peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $loans->links() }}
        </div>
    </div>
</x-app-layout>