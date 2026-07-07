<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::patch('/members/{member}/status', [MemberController::class, 'updateStatus'])->name('members.updateStatus');

//ini hanya route sementara untuk menampilkan halaman buku, pembayaran, dan laporan, nanti akan diganti dengan controller
    Route::get('/books', function () {
    return view('admin.book', [
        'books' => \App\Models\Book::latest()->paginate(10),
        'categories' => \App\Models\Category::all(),
    ]);
})->name('books.index');

Route::get('/books/create', function () {
    return view('admin.book-form', [
        'categories' => \App\Models\Category::all(),
    ]);
})->name('books.create');

Route::get('/books/{book}/edit', function (\App\Models\Book $book) {
    return view('admin.book-form', [
        'book' => $book,
        'categories' => \App\Models\Category::all(),
    ]);
})->name('books.edit');

Route::get('/payments', function () {
    return view('admin.payment', [
        'receipts' => \App\Models\PaymentReceipt::with('fine.loan.user', 'fine.loan.book')
            ->latest()
            ->paginate(10),
    ]);
})->name('payments.index');

Route::patch('/payments/{receipt}/approve', function (\App\Models\PaymentReceipt $receipt) {
    return back();
})->name('payments.approve');

Route::patch('/payments/{receipt}/reject', function (\App\Models\PaymentReceipt $receipt) {
    return back();
})->name('payments.reject');

Route::get('/reports', function (\Illuminate\Http\Request $request) {
    $from = $request->filled('from') ? \Carbon\Carbon::parse($request->from) : now()->startOfMonth();
    $to = $request->filled('to') ? \Carbon\Carbon::parse($request->to) : now()->endOfMonth();

    $transactions = \App\Models\Loan::with('user', 'book', 'fine')
        ->whereBetween('borrowed_at', [$from, $to])
        ->latest('borrowed_at')
        ->paginate(10);

    // Data grafik: jumlah peminjaman per bulan, 6 bulan terakhir
    $chartLabels = [];
    $chartData = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $chartLabels[] = $month->translatedFormat('M Y');
        $chartData[] = \App\Models\Loan::whereYear('borrowed_at', $month->year)
            ->whereMonth('borrowed_at', $month->month)
            ->count();
    }

    return view('admin.report', [
        'totalBooks' => \App\Models\Book::count(),
        'activeMembers' => \App\Models\User::where('role', 'member')->where('status', 'active')->count(),
        'totalLoans' => \App\Models\Loan::whereBetween('borrowed_at', [$from, $to])->count(),
        'totalFinesCollected' => \App\Models\Fine::where('status', 'paid')->sum('amount'),
        'chartLabels' => $chartLabels,
        'chartData' => $chartData,
        'transactions' => $transactions,
    ]);
})->name('reports.index');

Route::get('/reports/export', function () {
    return back()->with('error', 'Fitur export belum tersedia.');
})->name('reports.export');

    });

require __DIR__.'/auth.php';