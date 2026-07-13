<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $books = [
            ['title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'category' => 'fiksi', 'stock' => 5, 'isbn' => '9789793062792'],
            ['title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'category' => 'fiksi', 'stock' => 4, 'isbn' => '9789794338648'],
            ['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'category' => 'non-fiksi', 'stock' => 6, 'isbn' => '9780062316097'],
            ['title' => 'Cosmos', 'author' => 'Carl Sagan', 'category' => 'sains', 'stock' => 3, 'isbn' => '9780345539434'],
            ['title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'category' => 'sains', 'stock' => 4, 'isbn' => '9780553380163'],
            ['title' => 'Sejarah Indonesia Modern', 'author' => 'M.C. Ricklefs', 'category' => 'sejarah', 'stock' => 3, 'isbn' => '9789794613226'],
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'category' => 'teknologi', 'stock' => 5, 'isbn' => '9780132350884'],
            ['title' => 'The Pragmatic Programmer', 'author' => 'David Thomas', 'category' => 'teknologi', 'stock' => 4, 'isbn' => '9780135957059'],
            ['title' => 'Steve Jobs', 'author' => 'Walter Isaacson', 'category' => 'biografi', 'stock' => 3, 'isbn' => '9781451648539'],
            ['title' => 'Total Football', 'author' => 'David Winner', 'category' => 'non-fiksi', 'stock' => 2, 'isbn' => '9780747571801'],
        ];

        foreach ($books as $book) {
            Book::firstOrCreate(
                ['isbn' => $book['isbn']],
                [
                    'title'       => $book['title'],
                    'author'      => $book['author'],
                    'category_id' => $categories[$book['category']]->id ?? null,
                    'stock'       => $book['stock'],
                    'description' => "Buku {$book['title']} oleh {$book['author']}.",
                ]
            );
        }
    }
}