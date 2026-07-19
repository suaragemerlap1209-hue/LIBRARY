<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanDueSoonNotification extends Notification
{
    use Queueable;

    public function __construct(public Loan $loan) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Pengingat: Buku Akan Jatuh Tempo dalam 3 Hari')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Buku berikut akan jatuh tempo dalam **3 hari**:')
            ->line('📚 **' . $this->loan->book->title . '**')
            ->line('📅 Jatuh Tempo: **' . $this->loan->due_at->translatedFormat('d F Y') . '**')
            ->line('Segera kembalikan atau lakukan perpanjangan untuk menghindari denda.')
            ->action('Lihat Peminjaman Saya', url('/member/loans'))
            ->line('Denda keterlambatan: **Rp1.000/hari** sejak tanggal jatuh tempo.')
            ->salutation('Terima kasih, Lumina Library');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'      => 'due_soon',
            'loan_id'   => $this->loan->id,
            'book_title'=> $this->loan->book->title,
            'due_at'    => $this->loan->due_at->format('Y-m-d'),
            'message'   => 'Buku "' . $this->loan->book->title . '" akan jatuh tempo pada ' . $this->loan->due_at->translatedFormat('d F Y'),
        ];
    }
}