<?php

namespace App\Console\Commands;

use App\Models\Fine;
use App\Models\Loan;
use App\Notifications\FineIssuedNotification;
use Illuminate\Console\Command;

class CheckOverdueLoans extends Command
{
    protected $signature = 'loans:check-overdue';
    protected $description = 'Cek pinjaman yang sudah lewat jatuh tempo, tandai overdue, buat denda, dan kirim notifikasi';

    protected int $ratePerDay = 1000;

    public function handle(): int
    {
        $overdueLoans = Loan::whereIn('status', ['active', 'pending'])
            ->whereNotNull('due_at')
            ->whereNull('returned_at')
            ->where('due_at', '<', now()->toDateString())
            ->get();

        $count = 0;

        foreach ($overdueLoans as $loan) {
            $loan->update(['status' => 'overdue']);

            $daysLate = now()->startOfDay()->diffInDays($loan->due_at);
            $amount = $daysLate * $this->ratePerDay;

            $existingFine = Fine::where('loan_id', $loan->id)->first();

            if (! $existingFine) {
                $fine = Fine::create([
                    'loan_id' => $loan->id,
                    'amount' => $amount,
                    'status' => 'unpaid',
                ]);

                $loan->user->notify(new FineIssuedNotification($fine));
            }

            $count++;
        }

        $this->info("Selesai. {$count} pinjaman ditandai overdue (dari total {$overdueLoans->count()} yang dicek).");

        return self::SUCCESS;
    }
}