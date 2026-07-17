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
    /**
     * Run the database seeds.
     * Membuat 1 pinjaman overdue + 1 denda untuk SETIAP user dengan role member,
     * supaya siapapun akun member yang dipakai login, datanya pasti ada.
     */
    public function run(): void
    {
        $members = User::where('role', 'member')->get();
        $book = Book::first();

        if ($members->isEmpty() || ! $book) {
            $this->command->warn('DummyFineSeeder dilewati: tidak ada user member atau buku sama sekali.');
            return;
        }

        foreach ($members as $member) {
            // Hapus dummy lama milik user ini saja (supaya tidak menumpuk tiap dijalankan ulang)
            $oldLoan = Loan::where('user_id', $member->id)
                ->where('book_id', $book->id)
                ->where('status', 'overdue')
                ->first();

            if ($oldLoan) {
                Fine::where('loan_id', $oldLoan->id)->delete();
                $oldLoan->delete();
            }

            // Buat loan yang sudah terlambat 7 hari
            $loan = Loan::create([
                'user_id' => $member->id,
                'book_id' => $book->id,
                'borrowed_at' => Carbon::now()->subDays(14),
                'due_at' => Carbon::now()->subDays(7),
                'returned_at' => null,
                'status' => 'overdue',
            ]);

            // 7 hari terlambat x Rp1.000
            Fine::create([
                'loan_id' => $loan->id,
                'amount' => 7000,
                'status' => 'unpaid',
            ]);
        }

        $this->command->info("Dummy Fine berhasil dibuat untuk {$members->count()} member.");
    }
}