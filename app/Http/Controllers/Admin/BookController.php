<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        return view('admin.book', [
            'books' => Book::with('category')->latest()->paginate(10),
            'categories' => Category::all(),
        ]);
    }

    public function create()
    {
        return view('admin.book-from', [
            'categories' => Category::all(),
        ]);
    }

  public function store(Request $request)
{
    $data = $this->validateBook($request);
    if ($request->hasFile('cover')) {
        $data['cover'] = $request->file('cover')->store('books', 'public');
    }
    Book::create($data);
    return redirect()->route('admin.books.index')
        ->with('success', 'Buku berhasil ditambahkan.');
}

    public function edit(Book $book)
    {
        return view('admin.book-from', [
            'book' => $book,
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $data = $this->validateBook($request, $book);

        if ($request->hasFile('cover')) {
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $data['cover'] = $request->file('cover')->store('books', 'public');
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return back()->with('success', 'Buku berhasil dihapus.');
    }

    private function validateBook(Request $request, ?Book $book = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50|unique:books,isbn' . ($book ? ",{$book->id}" : ''),
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);
    }
}