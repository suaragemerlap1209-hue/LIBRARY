<?php
// app/Http/Controllers/Member/FineController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::whereHas('loan', fn($q) => $q->where('user_id', Auth::id()))
            ->with('loan.book')
            ->latest('updated_at')
            ->get();

        return view('member.fine', [
            // Tampilkan unpaid + pending + processing (belum selesai)
            'unpaidFines'  => $fines->whereIn('status', ['unpaid', 'pending', 'processing'])->values(),
            // Hanya yang sudah lunas
            'historyFines' => $fines->where('status', 'paid')->values(),
        ]);
    }

    public function pay(Fine $fine)
    {
        if ($fine->loan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized: Denda ini bukan milik Anda.');
        }

        // ✅ Status 'processing' juga boleh redirect ke checkout
        // (supaya member bisa lanjut bayar kalau sempat tutup popup)
        if (!in_array($fine->status, ['unpaid', 'pending', 'processing'])) {
            return back()->with('error', 'Denda ini sudah dibayar.');
        }

        return redirect()->route('payment.checkout', $fine);
    }
}