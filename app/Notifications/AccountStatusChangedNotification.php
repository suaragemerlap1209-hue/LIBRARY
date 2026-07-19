<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $newStatus, // 'suspended' | 'blocked' | 'active'
        public float $totalUnpaid
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $label = match ($this->newStatus) {
            'suspended' => 'ditangguhkan sementara',
            'blocked' => 'diblokir',
            default => 'diaktifkan kembali',
        };

        return (new MailMessage)
            ->subject('Perubahan Status Akun - Lumina Library')
            ->greeting("Halo {$notifiable->name},")
            ->line("Akun kamu telah **{$label}** karena total denda belum dibayar sebesar $" . number_format($this->totalUnpaid, 2) . ".")
            ->action('Lihat & Bayar Denda', route('member.payments.index'))
            ->line('Segera lunasi denda untuk memulihkan status akun kamu.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'account_status_changed',
            'status' => $this->newStatus,
            'total_unpaid' => $this->totalUnpaid,
            'message' => "Status akun kamu berubah menjadi \"{$this->newStatus}\" karena denda belum dibayar ($" . number_format($this->totalUnpaid, 2) . ").",
        ];
    }
}