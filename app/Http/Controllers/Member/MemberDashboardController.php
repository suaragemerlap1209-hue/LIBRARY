<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MemberDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $member = [
            'name'      => $user->name,
            'role'      => 'Anggota',
            'member_id' => $user->member_id ?? '-',
            'avatar'    => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1D3B2C&color=fff',
        ];

        $today = now()->translatedFormat('l, d F Y');

        $allLoans = $user->loans()->with('book', 'fine')->latest('borrowed_at')->get();

        $borrowedActive = $allLoans->whereIn('status', ['pending', 'active', 'overdue']);
        $lateCount = $allLoans->where('status', 'overdue')->count();

        $stats = [
            'borrowed' => [
                'total' => $borrowedActive->count(),
                'note'  => $borrowedActive->where('borrowed_at', '>=', now()->subWeek())->count() . ' buku baru minggu ini',
            ],
            'late' => [
                'total' => $lateCount,
                'note'  => $lateCount > 0 ? 'Segera kembalikan' : 'Tidak ada keterlambatan',
            ],
            'fine_total' => $user->loans()->whereHas('fine', fn($q) => $q->where('status', 'unpaid'))
                ->with('fine')->get()->sum(fn($loan) => $loan->fine->amount ?? 0),
        ];

        $loans = $allLoans->take(5)->map(function ($loan) {
            $statusMap = [
                'pending'  => 'aktif',
                'active'   => 'aktif',
                'overdue'  => 'terlambat',
                'returned' => 'selesai',
            ];

            return [
                'title'       => $loan->book->title,
                'author'      => $loan->book->author,
                'cover'       => $loan->book->cover
                    ? asset('storage/' . $loan->book->cover)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($loan->book->title) . '&background=random',
                'status'      => $statusMap[$loan->status] ?? $loan->status,
                'borrowed_at' => $loan->borrowed_at ? Carbon::parse($loan->borrowed_at)->translatedFormat('d M') : '-',
                'due_at'      => $loan->due_at ? Carbon::parse($loan->due_at)->translatedFormat('d M') : '-',
                'returned_at' => $loan->returned_at ? Carbon::parse($loan->returned_at)->translatedFormat('d M Y') : null,
            ];
        });
        $unpaidFine = $user->loans()
            ->whereHas('fine', fn($q) => $q->where('status', 'unpaid'))
            ->with('fine')
            ->get()
            ->sum(fn($loan) => $loan->fine->amount ?? 0);

        $fine = [
            'total'     => $unpaidFine,
            'late_fee'  => $unpaidFine,
            'admin_fee' => 0,
        ];

        $recommendations = Book::inRandomOrder()->limit(2)->get()->map(function ($book) {
            return [
                'title'    => $book->title,
                'category' => $book->category->name ?? 'Umum',
                'cover'    => $book->cover
                    ? asset('storage/' . $book->cover)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($book->title) . '&background=random',
            ];
        });
        return view('member.dashboard', compact(
            'member', 'today', 'stats', 'loans', 'fine', 'recommendations'
        ));
    }

    public function settings()
{
    return view('member.settings', [
        'user' => Auth::user(),
    ]);
}

public function help()
{
    return view('member.help');
}

}