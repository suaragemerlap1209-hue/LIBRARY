<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use App\Models\PaymentReceipt;
use App\Notifications\PaymentApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        $fines = Fine::with('loan.book', 'receipt')
            ->whereHas('loan', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        $totalFine = $fines->where('status', '!=', 'paid')->sum('amount');

        return view('member.payment.index', [
            'fines' => $fines,
            'totalFine' => $totalFine,
            'clientKey' => config('midtrans.client_key'),
            'isProduction' => config('midtrans.is_production'),
        ]);
    }

    public function show(Fine $fine)
    {
        abort_if($fine->loan->user_id != Auth::id(), 403);

        return view('member.payment.show', [
            'fine' => $fine->load('loan.book', 'receipt'),
            'clientKey' => config('midtrans.client_key'),
            'isProduction' => config('midtrans.is_production'),
        ]);
    }

    /**
     * Placeholder pembayaran manual (disimpan sebagai fallback,
     * saat ini alur utama pembayaran memakai Midtrans).
     */
    public function manual(Fine $fine)
    {
        abort_if($fine->loan->user_id != Auth::id(), 403);

        return back()->with('success', 'Pembayaran manual berhasil dikirim dan sedang menunggu verifikasi admin.');
    }

    /**
     * Generate Snap token untuk 1 denda tertentu.
     * Dipanggil via fetch() (AJAX) dari tombol "Bayar dengan Midtrans".
     */
    public function midtrans(Fine $fine)
    {
        abort_if($fine->loan->user_id != Auth::id(), 403);

        if ($fine->status !== 'unpaid') {
            return response()->json(['message' => 'Denda ini sudah diajukan/dibayar sebelumnya.'], 422);
        }

        $user = Auth::user();
        $orderId = 'FINE-' . $fine->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round($fine->amount),
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [[
                'id' => 'fine-' . $fine->id,
                'price' => (int) round($fine->amount),
                'quantity' => 1,
                'name' => 'Denda: ' . ($fine->loan->book->title ?? 'Buku'),
            ]],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap token error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal membuat sesi pembayaran. Coba lagi nanti.'], 500);
        }

        PaymentReceipt::updateOrCreate(
            ['fine_id' => $fine->id],
            [
                'transaction_id' => $orderId,
                'payment_method' => 'midtrans',
                'snap_token' => $snapToken,
                'status' => 'pending',
            ]
        );

        return response()->json(['snap_token' => $snapToken]);
    }

    /**
     * Webhook notification dari Midtrans (server-to-server).
     * Route ini public (tanpa auth) dan di-exclude dari CSRF di bootstrap/app.php.
     */
    public function callback(Request $request)
    {
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['message' => 'invalid notification'], 400);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        preg_match('/^FINE-(\d+)-/', $orderId, $matches);
        $fineId = $matches[1] ?? null;

        $fine = Fine::find($fineId);

        if (! $fine) {
            Log::warning("Midtrans callback: Fine ID {$fineId} tidak ditemukan (order_id: {$orderId}).");
            return response()->json(['message' => 'fine not found'], 404);
        }

        $receipt = $fine->receipt;

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            if ($fraudStatus === 'accept' || is_null($fraudStatus)) {
                $fine->update(['status' => 'paid']);
                $receipt?->update(['status' => 'approved']);
                $fine->loan->user->notify(new PaymentApprovedNotification($fine));
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $fine->update(['status' => 'unpaid']);
            $receipt?->update(['status' => 'rejected']);
        } elseif ($transactionStatus === 'pending') {
            $fine->update(['status' => 'pending']);
            $receipt?->update(['status' => 'pending']);
        }

        return response()->json(['message' => 'ok']);
    }
}