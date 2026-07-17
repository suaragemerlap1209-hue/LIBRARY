<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentReceipt;
use App\Notifications\PaymentApprovedNotification;
use App\Notifications\PaymentRejectedNotification;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index()
    {
        return view('admin.payment', [
            'receipts' => PaymentReceipt::with('fine.loan.user', 'fine.loan.book')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function approve(PaymentReceipt $receipt)
    {
        if ($receipt->status !== 'pending') {
            return back()->with('error', 'Bukti pembayaran ini sudah diproses sebelumnya.');
        }

        $receipt->update(['status' => 'approved']);
        $receipt->fine->update(['status' => 'paid']);

        // Turunkan status akun user kalau denda lain sudah tidak ada yang unpaid melewati ambang batas
        $user = $receipt->fine->loan->user;
        $stillUnpaid = $user->loans()
            ->whereHas('fine', fn ($q) => $q->where('status', 'unpaid'))
            ->with('fine')
            ->get()
            ->sum(fn ($loan) => $loan->fine->amount ?? 0);

        if ($stillUnpaid < 10 && $user->status !== 'active') {
            $user->update(['status' => 'active']);
        }

        $user->notify(new PaymentApprovedNotification($receipt->fine));

        return back()->with('success', 'Pembayaran berhasil disetujui.');
    }

    public function reject(Request $request, PaymentReceipt $receipt)
    {
        if ($receipt->status !== 'pending') {
            return back()->with('error', 'Bukti pembayaran ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $receipt->update(['status' => 'rejected']);
        $receipt->fine->update(['status' => 'unpaid']);

        $receipt->fine->loan->user->notify(
            new PaymentRejectedNotification($receipt->fine, $request->input('reason'))
        );

        return back()->with('success', 'Pembayaran ditolak, member akan diminta upload ulang.');
    }
}