@extends('layouts.member')

@section('title', 'Loans')
@section('page-title', 'Active Loans')

@section('topbar-search')
    <form method="GET" action="{{ route('member.loans.index') }}" class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search my loans..."
               class="w-72 pl-9 pr-4 py-2 rounded-full bg-white border border-stone-200 text-sm
                      placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]/30">
    </form>
@endsection

@section('content')

    @php
        $user = Auth::user();
        $activeCount = $loans->whereIn('status', ['pending', 'active', 'overdue'])->count();
        $unpaidFines = $loans->filter(fn ($loan) => $loan->fine && $loan->fine->status !== 'paid');
        $totalUnpaid = $unpaidFines->sum(fn ($loan) => $loan->fine->amount ?? 0);
        $isGoodStanding = $unpaidFines->isEmpty() && $user->status === 'active';
    @endphp

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-[#DCEBD9] text-[#2F5233] text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-100 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===== HERO + ACCOUNT STATUS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-[#152B1E] rounded-2xl p-8 relative overflow-hidden">
            <h2 class="text-2xl font-semibold text-white mb-3">Hello, {{ $user->name }}</h2>
            <p class="text-stone-300 max-w-md leading-relaxed">
                You currently have {{ $activeCount }} {{ Str::plural('book', $activeCount) }} borrowed.
                Remember to return or renew your items before the due date to avoid fines.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6">
            <p class="text-xs text-stone-400 uppercase tracking-wide mb-2">Account Status</p>
            <h3 class="text-2xl font-semibold text-stone-900 mb-4">
                {{ $isGoodStanding ? 'Good Standing' : 'Attention Needed' }}
            </h3>
            <div class="flex items-center gap-2 text-sm {{ $isGoodStanding ? 'text-[#2F5233]' : 'text-red-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ $isGoodStanding ? 'No outstanding fines' : 'You have unpaid fines' }}
            </div>
        </div>
    </div>

    {{-- ===== BOOKSHELF ===== --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-semibold text-stone-900">Your Bookshelf</h3>
    </div>

    @if ($loans->isEmpty())
        <div class="bg-white border border-stone-100 rounded-2xl p-10 text-center text-stone-500 mb-6">
            Kamu belum pernah meminjam buku.
            <a href="{{ route('member.catalog.index') }}" class="text-[#2F5233] font-medium hover:underline">
                Jelajahi katalog
            </a>
        </div>
    @else
        <div class="flex flex-col gap-4 mb-6">
            @foreach ($loans as $loan)
                @php
                    $cover = $loan->book->cover
                        ? asset('storage/' . $loan->book->cover)
                        : 'https://placehold.co/160x200/2f4f3f/e8dcc4?text=' . urlencode($loan->book->title);

                    // Selisih hari ke due_at: positif = masih ada waktu, negatif = sudah lewat
                    $timeLeft = $loan->status !== 'returned'
                        ? now()->startOfDay()->diffInDays($loan->due_at->copy()->startOfDay(), false)
                        : null;
                @endphp

                <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6 flex flex-col sm:flex-row gap-6">
                    <img src="{{ $cover }}" alt="{{ $loan->book->title }}"
                         class="w-24 h-32 object-cover rounded-xl shrink-0">

                    <div class="flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div>
                                <h4 class="text-lg font-semibold text-stone-900">{{ $loan->book->title }}</h4>
                                <p class="text-sm text-stone-500">
                                    by {{ $loan->book->author }}
                                    @if ($loan->book->category)
                                        • {{ $loan->book->category->name }}
                                    @endif
                                </p>
                            </div>
                            <x-badge-status :status="$loan->status" />
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-stone-400 uppercase tracking-wide">Loan Date</p>
                                <p class="text-sm font-medium text-stone-800">{{ $loan->borrowed_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-stone-400 uppercase tracking-wide">Due Date</p>
                                <p class="text-sm font-medium {{ $loan->status === 'overdue' ? 'text-red-600' : 'text-stone-800' }}">
                                    {{ $loan->due_at->format('M d, Y') }}
                                </p>
                            </div>
                            @if ($timeLeft !== null)
                                <div>
                                    <p class="text-xs text-stone-400 uppercase tracking-wide">Time Left</p>
                                    <p class="text-sm font-medium {{ $timeLeft < 0 ? 'text-red-600' : 'text-stone-800' }}">
                                        {{ $timeLeft }} Days
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-3 mt-auto">
                            <form method="POST" action="{{ route('member.loans.return', $loan) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="bg-[#152B1E] text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-[#1f3d29] transition">
                                    Return Now
                                </button>
                            </form>

                            @if ($loan->status === 'overdue')
                                <button type="button" disabled
                                        class="border border-stone-200 text-stone-400 text-sm font-semibold px-5 py-2.5 rounded-lg cursor-not-allowed">
                                    Renew (Unavailable)
                                </button>
                            @else
                                <form method="POST" action="{{ route('member.loans.renew', $loan) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="border border-stone-300 text-stone-700 text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-stone-50 transition">
                                        Renew
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mb-6">
            {{ $loans->links() }}
        </div>
    @endif

    {{-- ===== UNPAID FINES BANNER ===== --}}
    @if ($unpaidFines->isNotEmpty())
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-red-700">Unpaid Overdue Fines</p>
                    <p class="text-sm text-red-600">
                        You have a balance of ${{ number_format($totalUnpaid, 2) }} due for
                        {{ $unpaidFines->pluck('book.title')->join(', ') }}.
                    </p>
                </div>
            </div>
            <a href="{{ Route::has('member.payments.index') ? route('member.payments.index') : '#' }}"
               class="bg-red-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-red-700 transition whitespace-nowrap">
                Pay Now
            </a>
        </div>
    @endif

@endsection