<?php

namespace App\Console\Commands;

use App\Models\Fine;
use App\Models\Loan;
use App\Notifications\LoanDueSoonNotification;
use App\Notifications\LoanOverdueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateOverdueFines extends Command
{
    protected $signature   = 'fines:calculate';
    protected $description = 'Hitung denda, sanksi berjenjang, dan kirim notifikasi';

    public function handle(): void
    {
        $today = Carbon::today()->startOfDay();

        // ===== BAGIAN 1: Reminder H-3 =====
        $dueSoonLoans = Loan::with(['user', 'book'])
            ->whereIn('status', ['active'])
            ->whereNotNull('due_at')
            ->whereNull('reminder_sent_at')
            ->whereDate('due_at', $today->copy()->addDays(3))
            ->get();

        $this->info("Reminder H-3: {$dueSoonLoans->count()} peminjaman.");

        foreach ($dueSoonLoans as $loan) {
            $loan->user->notify(new LoanDueSoonNotification($loan));
            $loan->update(['reminder_sent_at' => now()]);
            $this->line("Reminder terkirim → {$loan->user->name} — {$loan->book->title}");
        }

        // ===== BAGIAN 2: Overdue — hitung denda + sanksi + notif =====
        $overdueLoans = Loan::with(['fine', 'user', 'book'])
            ->where('status', 'active')
            ->whereNotNull('due_at')
            ->where('due_at', '<', $today)
            ->get();

        $this->info("Overdue ditemukan: {$overdueLoans->count()} peminjaman.");

        foreach ($overdueLoans as $loan) {
            $loan->update(['status' => 'overdue']);

            $daysLate = (int) Carbon::parse($loan->due_at)->startOfDay()->diffInDays($today);
            $amount   = $daysLate * 1000;

            // Hitung / update fine
            $existingFine = $loan->fine;

            if ($existingFine) {
                if (in_array($existingFine->status, ['processing', 'paid'])) {
                    $this->line("Skip Fine #{$existingFine->id} — status: {$existingFine->status}");
                    continue;
                }
                $existingFine->update(['amount' => $amount]);
                $this->line("Updated Fine #{$existingFine->id} — {$daysLate} hari — Rp{$amount}");
            } else {
                Fine::create([
                    'loan_id' => $loan->id,
                    'amount'  => $amount,
                    'status'  => 'unpaid',
                ]);
                $this->line("Created Fine — Loan #{$loan->id} — {$daysLate} hari — Rp{$amount}");
            }

            // Sanksi berjenjang
            $user = $loan->user;
            if ($daysLate > 30 && $user->status !== 'blocked') {
                $user->update(['status' => 'blocked']);
                $this->line("BLOCKED → {$user->name} ({$daysLate} hari)");
            } elseif ($daysLate >= 8 && $daysLate <= 30 && $user->status === 'active') {
                $user->update(['status' => 'suspended']);
                $this->line("SUSPENDED → {$user->name} ({$daysLate} hari)");
            }

            // Kirim notifikasi overdue
            $loan->user->notify(new LoanOverdueNotification($loan, $daysLate, $amount));
            $this->line("Notif overdue terkirim → {$loan->user->name}");
        }

        $this->info('Selesai!');
    }
}