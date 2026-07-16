<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        return view('member.loan', [
            'loans' => Auth::user()->loans()
                ->whereIn('status', ['pending', 'active', 'overdue'])
                ->with('book.category')
                ->latest('created_at')
                ->paginate(10),
        ]);
    }

    public function history(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Auth::user()->loans()->with(['book.category', 'fine']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('book', fn ($b) => $b->where('title', 'like', "%{$search}%"));
        }

        return view('member.history', [
            'loans'  => $query->latest('created_at')->paginate(10)->withQueryString(),
            'status' => $status,
        ]);
    }

    public function store(Book $book)
    {
        $user = Auth::user();

        if ($book->stock < 1) {
            return back()->with('error', 'Stok buku ini sedang habis.');
        }

        $activeLoans = $user->loans()->whereIn('status', ['pending', 'active', 'overdue'])->count();

        if ($activeLoans >= $user->max_loans) {
            return back()->with('error', "Kamu sudah mencapai batas maksimal peminjaman ({$user->max_loans} buku).");
        }

        $alreadyBorrowed = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'active', 'overdue'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Kamu masih meminjam buku ini.');
        }

        DB::transaction(function () use ($user, $book) {
            // borrowed_at & due_at sengaja tidak diisi di sini.
            // Baru di-set saat admin approve.
            Loan::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
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

        if (! in_array($loan->status, ['active', 'overdue'])) {
            return back()->with('error', 'Buku ini tidak bisa dikembalikan.');
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

        if ($loan->status !== 'active') {
            return back()->with('error', 'Peminjaman ini tidak bisa diperpanjang.');
        }

        $loan->update([
            'due_at' => $loan->due_at->addDays(31),
        ]);

        return back()->with('success', "Masa pinjam '{$loan->book->title}' berhasil diperpanjang 31 hari.");
    }
}