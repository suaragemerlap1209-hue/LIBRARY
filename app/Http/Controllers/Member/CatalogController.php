<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('category') && $request->category !== 'Semua Koleksi') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('author', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->availability === 'borrowed') {
                $query->where('stock', '<=', 0);
            }
        }

        match ($request->get('sort', 'latest')) {
            'title_asc'  => $query->orderBy('title', 'asc'),
            'title_desc' => $query->orderBy('title', 'desc'),
            default      => $query->latest(),
        };

        return view('member.catalog', [
            'books' => $query->latest()->get(),
            'categories' => Category::pluck('name'),
        ]);
    }

    public function show(Book $book)
    {
        return view('member.catalog-show', [
            'book' => $book->load('category'),
        ]);
    }
}