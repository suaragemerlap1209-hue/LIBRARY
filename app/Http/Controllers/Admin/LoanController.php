<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'book']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('admin.loan', [
            'loans' => $query->latest('borrowed_at')->paginate(10),
        ]);
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $loan->update(['status' => 'active']);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function markReturned(Loan $loan)
    {
        if (!in_array($loan->status, ['active', 'overdue'])) {
            return back()->with('error', 'Buku ini belum bisa ditandai kembali.');
        }

        $loan->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        $loan->book()->increment('stock');

        return back()->with('success', 'Buku ditandai sudah dikembalikan.');
    }
}