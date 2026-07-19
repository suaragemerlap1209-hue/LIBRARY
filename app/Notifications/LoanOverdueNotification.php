<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanOverdueNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Loan $loan,
        public int  $daysLate,
        public int  $amount
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $sanctionText = match(true) {
            $this->daysLate > 30 => '🚫 Akun Anda telah **diblokir** karena keterlambatan lebih dari 30 hari. Segera hubungi admin.',
            $this->daysLate >= 8 => '🔒 Akun Anda telah **ditangguhkan** karena keterlambatan lebih dari 7 hari.',
            default              => '⚠️ Anda tidak dapat meminjam buku baru selama denda belum dilunasi.',
        };

        return (new MailMessage)
            ->subject('🚨 Peringatan: Buku Terlambat Dikembalikan')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Buku berikut telah melewati batas waktu pengembalian:')
            ->line('📚 **' . $this->loan->book->title . '**')
            ->line('📅 Jatuh Tempo: **' . $this->loan->due_at->translatedFormat('d F Y') . '**')
            ->line('⏰ Keterlambatan: **' . $this->daysLate . ' hari**')
            ->line('💰 Total Denda: **Rp' . number_format($this->amount, 0, ',', '.') . '**')
            ->line($sanctionText)
            ->action('Bayar Denda Sekarang', url('/member/payments'))
            ->line('Segera kembalikan buku dan lunasi denda untuk memulihkan akun Anda.')
            ->salutation('Terima kasih, Lumina Library');
    }

    public function toArray($notifiable): array
    {
        return [
            'type'      => 'overdue',
            'loan_id'   => $this->loan->id,
            'book_title'=> $this->loan->book->title,
            'days_late' => $this->daysLate,
            'amount'    => $this->amount,
            'message'   => 'Buku "' . $this->loan->book->title . '" terlambat ' . $this->daysLate . ' hari. Denda: Rp' . number_format($this->amount, 0, ',', '.'),
        ];
    }
}