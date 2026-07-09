<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        return view('member.loan', [
            'loans' => Auth::user()->loans()->with('book')->latest('borrowed_at')->paginate(10),
        ]);
    }

    public function store(Book $book)
    {
        $user = Auth::user();

        // Cek apakah stok buku tersedia
        if ($book->stock < 1) {
            return back()->with('error', 'Stok buku ini sedang habis.');
        }

        // Hitung berapa banyak buku yang sedang dipinjam (belum dikembalikan)
        $activeLoans = $user->loans()->whereIn('status', ['pending', 'active', 'overdue'])->count();

        if ($activeLoans >= $user->max_loans) {
            return back()->with('error', "Kamu sudah mencapai batas maksimal peminjaman ({$user->max_loans} buku).");
        }

        // Cek apakah user sudah meminjam buku yang sama dan belum dikembalikan
        $alreadyBorrowed = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'active', 'overdue'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Kamu masih meminjam buku ini.');
        }

        DB::transaction(function () use ($user, $book) {
            Loan::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'due_at' => now()->addDays(14),
                'status' => 'pending',
            ]);

            $book->decrement('stock');
        });

        return redirect()->route('member.loans.index')->with('success', 'Peminjaman berhasil diajukan, menunggu persetujuan admin.');
    }
}