<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\PaymentReceipt;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'member')->first();

        if (! $user) {
            $this->command->warn('LoanSeeder dilewati: tidak ada user dengan role member.');
            return;
        }

        $book1 = Book::where('isbn', '978-01-111-08')->first();
        if ($book1) {
            $loan1 = Loan::firstOrCreate(
                ['user_id' => $user->id, 'book_id' => $book1->id, 'status' => 'overdue'],
                ['borrowed_at' => now()->subDays(31), 'due_at' => now()->subDays(3)]
            );
            if ($book1->stock > 0) { $book1->decrement('stock'); }
            Fine::firstOrCreate(['loan_id' => $loan1->id], ['amount' => 2.50, 'status' => 'unpaid']);
        }

        $book2 = Book::where('isbn', '978-01-111-09')->first();
        if ($book2) {
            $loan2 = Loan::firstOrCreate(
                ['user_id' => $user->id, 'book_id' => $book2->id, 'status' => 'active'],
                ['borrowed_at' => now()->subDays(14), 'due_at' => now()->addDays(14)]
            );
            if ($book2->stock > 0) { $book2->decrement('stock'); }
        }

        $book3 = Book::where('isbn', '978-01-111-10')->first();
        if ($book3) {
            $loan3 = Loan::firstOrCreate(
                ['user_id' => $user->id, 'book_id' => $book3->id, 'status' => 'returned'],
                ['borrowed_at' => now()->subDays(60), 'due_at' => now()->subDays(46), 'returned_at' => now()->subDays(40)]
            );
            $fine3 = Fine::firstOrCreate(['loan_id' => $loan3->id], ['amount' => 5.00, 'status' => 'paid']);
            PaymentReceipt::firstOrCreate(['fine_id' => $fine3->id], ['file_path' => 'payment-receipts/demo-receipt.pdf', 'status' => 'approved']);
        }
    }
}
