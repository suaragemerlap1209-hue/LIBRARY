<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->filled('from') ? Carbon::parse($request->from) : now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->to) : now()->endOfMonth();

        $transactions = Loan::with('user', 'book', 'fine')
            ->whereBetween('borrowed_at', [$from, $to])
            ->latest('borrowed_at')
            ->paginate(10);

        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M Y');
            $chartData[] = Loan::whereYear('borrowed_at', $month->year)
                ->whereMonth('borrowed_at', $month->month)
                ->count();
        }

        return view('admin.report', [
            'totalBooks' => Book::count(),
            'activeMembers' => User::where('role', 'member')->where('status', 'active')->count(),
            'suspendedMembers' => User::where('role', 'member')->where('status', 'suspended')->count(),
            'blockedMembers' => User::where('role', 'member')->where('status', 'blocked')->count(),
            'totalLoans' => Loan::whereBetween('borrowed_at', [$from, $to])->count(),
            'totalFinesCollected' => Fine::where('status', 'paid')->sum('amount'),
            'totalFinesOutstanding' => Fine::where('status', 'unpaid')->sum('amount'),
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'transactions' => $transactions,
        ]);
    }

    public function export()
    {
        return back()->with('error', 'Fitur export belum tersedia.');
    }
}