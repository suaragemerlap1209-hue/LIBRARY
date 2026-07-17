<?php

namespace App\Notifications;

use App\Models\Fine;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Fine $fine)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran Disetujui - Lumina Library')
            ->greeting("Halo {$notifiable->name},")
            ->line('Pembayaran denda kamu sebesar $' . number_format($this->fine->amount, 2) . ' telah disetujui.')
            ->line('Terima kasih sudah melunasi tepat waktu.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_approved',
            'fine_id' => $this->fine->id,
            'amount' => $this->fine->amount,
            'message' => 'Pembayaran denda $' . number_format($this->fine->amount, 2) . ' telah disetujui.',
        ];
    }
}