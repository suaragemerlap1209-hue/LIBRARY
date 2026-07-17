<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyFineSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::where('role', 'member')->first();
        $book = Book::first();

        if (!$member || !$book) {
            $this->command->error('Member atau buku belum tersedia.');
            return;
        }

        $loan = Loan::firstOrCreate(
            [
                'user_id' => $member->id,
                'book_id' => $book->id,
            ],
            [
                'borrowed_at' => Carbon::now()->subDays(14),
                'due_at' => Carbon::now()->subDays(7),
                'returned_at' => null,
                'status' => 'overdue',
            ]
        );

        Fine::firstOrCreate(
            [
                'loan_id' => $loan->id,
            ],
            [
                'amount' => 7000,
                'status' => 'unpaid',
            ]
        );

        $this->command->info('Dummy Fine berhasil dibuat.');
    }
}