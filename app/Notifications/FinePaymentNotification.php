<?php

namespace App\Notifications;

use App\Models\Fine;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinePaymentNotification extends Notification
{
    use Queueable;

    public function __construct(public Fine $fine) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $method = $this->fine->payment_type === 'cash' ? 'Tunai (Kasir)' : 'Midtrans Online';

        return (new MailMessage)
            ->subject('✅ Pembayaran Denda Berhasil')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Pembayaran denda Anda telah berhasil dikonfirmasi.')
            ->line('📚 **' . ($this->fine->loan->book->title ?? '-') . '**')
            ->line('💰 Nominal: **Rp' . number_format($this->fine->amount, 0, ',', '.') . '**')
            ->line('💳 Metode: **' . $method . '**')
            ->line('📅 Tanggal Bayar: **' . $this->fine->paid_at?->translatedFormat('d F Y') . '**')
            ->line('✅ Status akun Anda telah dipulihkan menjadi **Aktif**.')
            ->action('Lihat Riwayat Pembayaran', url('/member/payments'))
            ->salutation('Terima kasih, Lumina Library');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'      => 'fine_paid',
            'fine_id'   => $this->fine->id,
            'book_title'=> $this->fine->loan->book->title ?? '-',
            'amount'    => $this->fine->amount,
            'message'   => 'Denda Rp' . number_format($this->fine->amount, 0, ',', '.') . ' untuk buku "' . ($this->fine->loan->book->title ?? '-') . '" telah lunas.',
        ];
    }
}