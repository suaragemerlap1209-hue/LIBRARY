<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateBookCovers extends Command
{
    protected $signature = 'books:generate-covers {--force : Timpa cover yang sudah ada}';
    protected $description = 'Generate cover buku secara lokal (SVG) - tidak butuh koneksi internet sama sekali';

    protected array $colors = [
        ['#2F4F3F', '#E8DCC4'],
        ['#1d3b2a', '#e8dcc4'],
        ['#8B5A2B', '#F3E9DC'],
        ['#152B1E', '#DCEBD9'],
        ['#4A3728', '#F5E6D3'],
        ['#2C5F2D', '#FFE8D6'],
    ];

    public function handle(): int
    {
        $force = $this->option('force');
        $books = Book::all();
        $count = 0;

        foreach ($books as $book) {
            $path = "covers/book-{$book->id}.svg";

            if (! $force && Storage::disk('public')->exists($path)) {
                $book->update(['cover' => $path]);
                continue;
            }

            [$bg, $fg] = $this->colors[$book->id % count($this->colors)];
            $title = htmlspecialchars($book->title, ENT_XML1);

            $lines = $this->wrapText($title, 18);
            $lineHeight = 28;
            $startY = 200 - (count($lines) * $lineHeight / 2);

            $textElements = '';
            foreach ($lines as $i => $line) {
                $y = $startY + ($i * $lineHeight);
                $textElements .= "<text x=\"150\" y=\"{$y}\" font-family=\"Georgia, serif\" font-size=\"20\" fill=\"{$fg}\" text-anchor=\"middle\">{$line}</text>";
            }

            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 400" width="300" height="400">
    <rect width="300" height="400" fill="{$bg}"/>
    <rect x="15" y="15" width="270" height="370" fill="none" stroke="{$fg}" stroke-width="1" opacity="0.4"/>
    {$textElements}
</svg>
SVG;

            Storage::disk('public')->put($path, $svg);
            $book->update(['cover' => $path]);
            $count++;
        }

        $this->info("Cover lokal berhasil dibuat/diperbarui untuk {$count} buku (dari total {$books->count()}).");

        return self::SUCCESS;
    }

    protected function wrapText(string $text, int $maxCharsPerLine): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $test = trim($current . ' ' . $word);
            if (strlen($test) > $maxCharsPerLine && $current !== '') {
                $lines[] = $current;
                $current = $word;
            } else {
                $current = $test;
            }
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        return array_slice($lines, 0, 4);
    }
}
