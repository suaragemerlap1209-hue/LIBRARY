<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class UpdateBookCoversSeeder extends Seeder
{
    /**
     * Isi cover semua buku pakai gambar asli dari Open Library Covers API,
     * berdasarkan ISBN yang sudah ada di database.
     *
     * Ini menyimpan URL LENGKAP (bukan path storage lokal) ke kolom `cover`,
     * jadi tidak perlu upload file manual satu-satu.
     */
    public function run(): void
    {
        $books = Book::whereNotNull('isbn')->get();
        $updated = 0;
        $skipped = 0;

        foreach ($books as $book) {
            $isbn = preg_replace('/[^0-9Xx]/', '', $book->isbn); // bersihkan strip/spasi

            if (strlen($isbn) < 10) {
                $skipped++;
                continue;
            }

            $book->update([
                'cover' => "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg",
            ]);

            $updated++;
        }

        $this->command->info("Cover diperbarui untuk {$updated} buku. Dilewati: {$skipped} (ISBN tidak valid).");
    }
}