<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Fine;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data Modul Anggota ---
        $activeMembers = User::where('role', 'member')->where('status', 'active')->count();
        $newMembersThisMonth = User::where('role', 'member')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // --- Data Modul Buku ---
        $totalBooks = Book::count();
        $newBooksThisWeek = Book::where('created_at', '>=', now()->subWeek())->count();

        // --- Data Modul Peminjaman ---
        $dailyLoans = Loan::whereDate('borrowed_at', today())->count();

        $overdueToday = Loan::where('status', 'overdue')
            ->orWhere(function ($query) {
                $query->where('status', 'active')
                      ->whereDate('due_at', '<', today());
            })
            ->count();

        $pendingLoans = Loan::with('user', 'book')
            ->where('status', 'pending')
            ->latest('created_at')
            ->take(5)
            ->get();

        // --- Data Modul Denda ---
        $dendaTertunda = Fine::where('status', 'unpaid')->sum('amount');

        // --- Sirkulasi mingguan (untuk chart) ---
        $weeklyCirculation = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyCirculation[] = [
                'label' => $date->translatedFormat('D'),
                'value' => Loan::whereDate('borrowed_at', $date)->count(),
            ];
        }

        // --- Aktivitas terbaru: gabungan member baru + peminjaman terbaru ---
        $recentMembers = User::where('role', 'member')
            ->latest()
            ->take(3)
            ->get()
            ->map(fn ($member) => [
                'title' => 'Anggota baru terdaftar',
                'description' => $member->name . ' (' . ($member->member_id ?? '-') . ')',
                'time' => $member->created_at->diffForHumans(),
                'timestamp' => $member->created_at,
                'color' => 'bg-blue-400',
            ]);

        $recentLoans = Loan::with('user', 'book')
            ->latest('created_at')
            ->take(3)
            ->get()
            ->map(fn ($loan) => [
                'title' => 'Peminjaman baru',
                'description' => $loan->user->name . ' meminjam "' . $loan->book->title . '"',
                'time' => $loan->created_at->diffForHumans(),
                'timestamp' => $loan->created_at,
                'color' => 'bg-amber-400',
            ]);

        $recentActivities = $recentMembers
            ->concat($recentLoans)
            ->sortByDesc('timestamp')
            ->take(5)
            ->values();

        return view('dashboard', compact(
            'activeMembers',
            'newMembersThisMonth',
            'recentActivities',
            'totalBooks',
            'newBooksThisWeek',
            'dailyLoans',
            'overdueToday',
            'pendingLoans',
            'dendaTertunda',
            'weeklyCirculation'
        ));
    }
}