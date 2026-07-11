<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::whereHas('loan', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with('loan.book', 'receipt')
            ->latest()
            ->get();

        return view('member.fine', [
            'unpaidFines' => $fines->where('status', 'unpaid'),
            'historyFines' => $fines->whereIn('status', ['pending', 'paid']),
        ]);
    }

    public function pay(Request $request, Fine $fine)
    {
        if ($fine->loan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($fine->status !== 'unpaid') {
            return back()->with('error', 'Denda ini sudah diajukan/dibayar sebelumnya.');
        }

        $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'proof.required' => 'Bukti pembayaran wajib diunggah.',
            'proof.mimes' => 'File harus berupa JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $path = $request->file('proof')->store('payment-receipts', 'public');

        $fine->receipt()->create([
            'file_path' => $path,
            'status' => 'pending',
        ]);

        $fine->update(['status' => 'pending']);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi admin.');
    }
}