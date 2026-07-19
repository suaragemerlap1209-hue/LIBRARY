<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Notifications\FinePaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function checkout(Fine $fine)
    {
        if ($fine->loan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized: Denda ini bukan milik Anda.');
        }

        if (!in_array($fine->status, ['unpaid', 'pending'])) {
            return redirect()->route('member.payments.index')
                ->with('error', 'Denda ini sudah diproses atau telah dibayar.');
        }

        if ($fine->status === 'processing' && $fine->snap_token) {
            return view('payment.checkout', [
                'fine'      => $fine,
                'snapToken' => $fine->snap_token,
            ]);
        }

        $user  = Auth::user();
        $order = 'FINE-' . $fine->id . '-' . time();

        try {
            $params = [
                'transaction_details' => [
                    'order_id'     => $order,
                    'gross_amount' => (int) $fine->amount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email'      => $user->email,
                ],
                'item_details' => [
                    [
                        'id'       => 'FINE-' . $fine->id,
                        'price'    => (int) $fine->amount,
                        'quantity' => 1,
                        'name'     => 'Denda Keterlambatan - ' . ($fine->loan->book->title ?? 'Unknown Book'),
                    ],
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $fine->update([
                'order_id'     => $order,
                'snap_token'   => $snapToken,
                'status'       => 'processing',
                'payment_type' => 'midtrans',
            ]);

            Log::info('Midtrans checkout created', [
                'fine_id'  => $fine->id,
                'order_id' => $order,
                'amount'   => $fine->amount,
                'user_id'  => Auth::id(),
            ]);

            return view('payment.checkout', compact('fine', 'snapToken'));

        } catch (\Exception $e) {
            Log::error('Midtrans checkout error', [
                'fine_id' => $fine->id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()->route('member.payments.index')
                ->with('error', 'Gagal membuat session pembayaran. Silakan coba lagi.');
        }
    }

    public function notification(Request $request)
    {
        try {
            $notif       = new Notification();
            $status      = $notif->transaction_status;
            $type        = $notif->payment_type;
            $orderId     = $notif->order_id;
            $fraudStatus = $notif->fraud_status ?? null;

            Log::info('Midtrans notification received', [
                'order_id' => $orderId,
                'status'   => $status,
                'type'     => $type,
            ]);

            $fine = Fine::with('loan.user')->where('order_id', $orderId)->first();

            if (!$fine) {
                Log::warning('Fine not found for order', ['order_id' => $orderId]);
                return response()->json(['message' => 'Fine not found'], 404);
            }

            $fine->transaction_id = $notif->transaction_id ?? null;

            if ($status == 'capture') {
                $fine->status  = ($type == 'credit_card' && $fraudStatus == 'challenge') ? 'pending' : 'paid';
                $fine->paid_at = now();

            } elseif ($status == 'settlement') {
                $fine->status  = 'paid';
                $fine->paid_at = now();

            } elseif (in_array($status, ['cancel', 'deny', 'expire'])) {
                $fine->status     = 'unpaid';
                $fine->paid_at    = null;
                $fine->snap_token = null;
                $fine->order_id   = null;

            } elseif ($status == 'pending') {
                $fine->status = 'pending';

            } else {
                Log::warning('Unknown Midtrans status', ['order_id' => $orderId, 'status' => $status]);
                $fine->status = 'unpaid';
            }

            $fine->save();

            // Kalau lunas — pulihkan user + kirim notif
            if ($fine->status === 'paid') {
                $this->handlePaidFine($fine);
            }

            Log::info('Fine updated from Midtrans', [
                'fine_id'    => $fine->id,
                'order_id'   => $orderId,
                'new_status' => $fine->status,
            ]);

            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            Log::error('Midtrans notification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error processed'], 200);
        }
    }

    private function handlePaidFine(Fine $fine): void
    {
        $user = $fine->loan->user;

        // Cek apakah semua fine user sudah lunas
        $hasUnpaidFines = $user->loans()
            ->whereHas('fine', fn($q) => $q->whereIn('status', ['unpaid', 'pending', 'processing']))
            ->exists();

        // Pulihkan status user kalau tidak ada denda lain
        if (!$hasUnpaidFines && in_array($user->status, ['suspended', 'blocked'])) {
            $user->update(['status' => 'active']);
            Log::info("User #{$user->id} status dipulihkan ke active");
        }

        // Kirim notifikasi email
        $user->notify(new FinePaymentNotification($fine));

        Log::info("FinePaymentNotification terkirim ke {$user->email}");
    }
}