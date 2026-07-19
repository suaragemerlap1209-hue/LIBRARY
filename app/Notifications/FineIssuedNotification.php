<?php

namespace App\Notifications;

use App\Models\Fine;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FineIssuedNotification extends Notification
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
        $book = $this->fine->loan->book;

        return (new MailMessage)
            ->subject('Denda Keterlambatan Buku - Lumina Library')
            ->greeting("Halo {$notifiable->name},")
            ->line("Buku \"{$book->title}\" sudah melewati batas waktu pengembalian.")
            ->line("Denda yang dikenakan: $" . number_format($this->fine->amount, 2))
            ->action('Bayar Sekarang', route('member.payments.index'))
            ->line('Silakan segera lunasi denda untuk menghindari suspensi akun.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'fine_issued',
            'fine_id' => $this->fine->id,
            'book_title' => $this->fine->loan->book->title ?? 'Buku',
            'amount' => $this->fine->amount,
            'message' => "Denda $" . number_format($this->fine->amount, 2) . " untuk keterlambatan buku \"{$this->fine->loan->book->title}\".",
        ];
    }
}