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
            'loans' => Auth::user()->loans()->with('book.category', 'fine')->latest('borrowed_at')->paginate(10),
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

    public function returnBook(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array($loan->status, ['pending', 'active', 'overdue'])) {
            return back()->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($loan) {
            $loan->update([
                'returned_at' => now(),
                'status' => 'returned',
            ]);

            $loan->book->increment('stock');
        });

        return back()->with('success', "Buku '{$loan->book->title}' berhasil dikembalikan.");
    }

    public function renew(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($loan->status === 'overdue') {
            return back()->with('error', 'Buku yang sudah lewat jatuh tempo tidak bisa diperpanjang. Silakan kembalikan dan bayar denda terlebih dahulu.');
        }

        if (! in_array($loan->status, ['pending', 'active'])) {
            return back()->with('error', 'Peminjaman ini tidak bisa diperpanjang.');
        }

        $loan->update([
            'due_at' => $loan->due_at->addDays(14),
        ]);

        return back()->with('success', "Masa pinjam '{$loan->book->title}' berhasil diperpanjang 14 hari.");
    }
}