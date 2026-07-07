<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data Modul Anggota (sudah tersedia, tanggung jawabmu) ---
        $activeMembers = User::where('role', 'member')->where('status', 'active')->count();
        $newMembersThisMonth = User::where('role', 'member')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // --- Aktivitas terbaru: sementara hanya dari member baru ---
        // Nanti akan digabung dengan aktivitas peminjaman/buku begitu Orang 2 selesai
        $recentActivities = User::where('role', 'member')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($member) => [
                'title' => 'Anggota baru terdaftar',
                'description' => $member->name . ' (' . $member->member_code . ')',
                'time' => $member->created_at->diffForHumans(),
                'color' => 'bg-blue-400',
            ]);

        // --- Data Modul Buku & Peminjaman: BELUM tersedia ---
        // Model Book.php & Loan.php adalah tanggung jawab Orang 2.
        // Sengaja dibiarkan null — Blade sudah punya fallback (?? 0 / ?? [])
        // sehingga halaman tetap tampil rapi tanpa data ini.
        $totalBooks = null;
        $newBooksThisWeek = null;
        $dailyLoans = null;
        $overdueToday = null;
        $pendingLoans = collect(); // koleksi kosong, biar @forelse tetap aman
        $weeklyCirculation = null; // biarkan Blade pakai dummy bawaannya

        return view('dashboard', compact(
            'activeMembers',
            'newMembersThisMonth',
            'recentActivities',
            'totalBooks',
            'newBooksThisWeek',
            'dailyLoans',
            'overdueToday',
            'pendingLoans',
            'weeklyCirculation'
        ));
    }
}