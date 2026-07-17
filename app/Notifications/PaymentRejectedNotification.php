<?php

namespace App\Notifications;

use App\Models\Fine;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Fine $fine,
        public ?string $reason = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Pembayaran Ditolak - Lumina Library')
            ->greeting("Halo {$notifiable->name},")
            ->line('Bukti pembayaran denda kamu sebesar $' . number_format($this->fine->amount, 2) . ' ditolak oleh admin.');

        if ($this->reason) {
            $mail->line("Alasan: {$this->reason}");
        }

        return $mail
            ->action('Upload Ulang Bukti Bayar', route('member.payments.index'))
            ->line('Silakan upload ulang bukti pembayaran yang valid.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_rejected',
            'fine_id' => $this->fine->id,
            'amount' => $this->fine->amount,
            'reason' => $this->reason,
            'message' => 'Pembayaran denda $' . number_format($this->fine->amount, 2) . ' ditolak. Silakan upload ulang.',
        ];
    }
}