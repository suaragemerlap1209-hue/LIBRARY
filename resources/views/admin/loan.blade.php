<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-[#1F2937]">Kelola Peminjaman (Testing)</h2>
    </x-slot>

    <div class="max-w-5xl">

        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filter status --}}
        <form method="GET" class="mb-6">
            <select name="status" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                <option value="active" @selected(request('status') == 'active')>Active</option>
                <option value="returned" @selected(request('status') == 'returned')>Returned</option>
                <option value="overdue" @selected(request('status') == 'overdue')>Overdue</option>
            </select>
        </form>

        <table class="w-full text-sm border rounded-xl overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Anggota</th>
                    <th class="p-3 text-left">Buku</th>
                    <th class="p-3 text-left">Tgl Pinjam</th>
                    <th class="p-3 text-left">Jatuh Tempo</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    <tr class="border-t">
                        <td class="p-3">{{ $loan->user->name }}</td>
                        <td class="p-3">{{ $loan->book->title }}</td>
                        <td class="p-3">{{ $loan->borrowed_at->format('d M Y') }}</td>
                        <td class="p-3">{{ $loan->due_at->format('d M Y') }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100">{{ $loan->status }}</span>
                        </td>
                        <td class="p-3 space-x-2">
                            @if($loan->status === 'pending')
                                <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-green-600 underline">Approve</button>
                                </form>
                            @endif

                            @if(in_array($loan->status, ['active', 'overdue']))
                                <form method="POST" action="{{ route('admin.loans.return', $loan) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-blue-600 underline">Tandai Kembali</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-3 text-center text-gray-500">Belum ada peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $loans->links() }}
        </div>
    </div>
</x-app-layout>