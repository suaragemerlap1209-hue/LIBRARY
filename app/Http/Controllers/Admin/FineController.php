<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use App\Notifications\FinePaymentNotification;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $query = Fine::with('loan.user', 'loan.book')
            ->latest('updated_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('loan.user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('loan.book', fn($b) => $b->where('title', 'like', "%{$search}%"));
            });
        }

        $fines = $query->paginate(15)->withQueryString();

        $stats = [
            'total_unpaid'     => Fine::where('status', 'unpaid')->count(),
            'total_pending'    => Fine::whereIn('status', ['pending', 'processing'])->count(),
            'total_paid'       => Fine::where('status', 'paid')->count(),
            'amount_collected' => Fine::where('status', 'paid')->sum('amount'),
        ];

        return view('admin.payment', compact('fines', 'stats'));
    }

    public function markPaid(Fine $fine)
    {
        if ($fine->status === 'processing') {
            return back()->with('error', 'Denda ini sedang dalam proses pembayaran Midtrans. Tunggu konfirmasi otomatis.');
        }

        if ($fine->status === 'paid') {
            return back()->with('error', 'Denda ini sudah lunas.');
        }

        $fine->update([
            'status'       => 'paid',
            'payment_type' => 'cash',
            'paid_at'      => now(),
        ]);

        // Pulihkan status user kalau tidak ada denda lain
        $user = $fine->load('loan.user')->loan->user;
        $hasUnpaidFines = $user->loans()
            ->whereHas('fine', fn($q) => $q->whereIn('status', ['unpaid', 'pending', 'processing']))
            ->exists();

        if (!$hasUnpaidFines && in_array($user->status, ['suspended', 'blocked'])) {
            $user->update(['status' => 'active']);
        }

        // Kirim notif email ke member
        $user->notify(new FinePaymentNotification($fine));

        return back()->with('success', "Denda #{$fine->id} berhasil ditandai lunas (tunai).");
    }
}