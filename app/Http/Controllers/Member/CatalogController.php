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

        if ($request->filled('category') && $request->category !== 'All Collections') {
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