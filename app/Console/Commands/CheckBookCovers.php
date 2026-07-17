<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class CheckBookCovers extends Command
{
    protected $signature = 'books:check-covers';
    protected $description = 'Tampilkan isi kolom cover semua buku (untuk debugging)';

    public function handle(): int
    {
        $books = Book::all(['id', 'title', 'cover']);

        foreach ($books as $book) {
            $status = $book->cover ? $book->cover : 'KOSONG';
            $this->line("{$book->id} - {$book->title} => {$status}");
        }

        $this->info("Total: {$books->count()} buku.");

        return self::SUCCESS;
    }
}
