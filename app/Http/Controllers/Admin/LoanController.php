<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = Loan::with(['user', 'book']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('member_id', 'like', "%{$search}%");
                })->orWhereHas('book', function ($b) use ($search) {
                    $b->where('title', 'like', "%{$search}%");
                });
            });
        }

        $counts = [
            'pending'  => Loan::where('status', 'pending')->count(),
            'active'   => Loan::where('status', 'active')->count(),
            'overdue'  => Loan::where('status', 'overdue')->count(),
            'returned' => Loan::where('status', 'returned')->count(),
        ];

        return view('admin.loan', [
            'loans'  => $query->latest('created_at')->paginate(10)->withQueryString(),
            'counts' => $counts,
            'status' => $status,
            'search' => $request->search,
        ]);
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $loan->update([
            'status' => 'active',
            'borrowed_at' => now(),
            'due_at' => now()->addDays(31),
        ]);

        return back()->with('success', 'Peminjaman disetujui, masa pinjam 31 hari dimulai hari ini.');
    }

    public function decline(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($loan) {
            $loan->book()->increment('stock');
            $loan->delete();
        });

        return back()->with('success', 'Permintaan peminjaman ditolak, stok buku dikembalikan.');
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