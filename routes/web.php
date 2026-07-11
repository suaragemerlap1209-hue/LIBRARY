<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Member\CatalogController;
use App\Http\Controllers\Member\LoanController as MemberLoanController;
use App\Http\Controllers\Member\FineController as MemberFineController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/katalog', function () {
    return view('pages.catalog', [
        'books' => \App\Models\Book::with('category')->latest()->get(),
        'categories' => \App\Models\Category::pluck('name'),
    ]);
})->name('catalog.public');

Route::get('/katalog/{book}', function (\App\Models\Book $book) {
    return view('pages.catalog-show', [
        'book' => $book->load('category'),
    ]);
})->name('catalog.show');

Route::get('/tentang', function () {
    return view('pages.about');
})->name('about');

Route::get('/bantuan', function () {
    return view('pages.help');
})->name('bantuan');


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

    // Books (CRUD)
    Route::resource('books', BookController::class)->except(['show']);

    // Loans
    Route::get('/loans', [AdminLoanController::class, 'index'])->name('loans.index');
    Route::patch('/loans/{loan}/approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
    Route::patch('/loans/{loan}/return', [AdminLoanController::class, 'markReturned'])->name('loans.return');

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

Route::middleware(['auth', 'verified', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');
    Route::post('/catalog/{book}/loan', [MemberLoanController::class, 'store'])->name('loans.store');
    Route::get('/loans', [MemberLoanController::class, 'index'])->name('loans.index');
    Route::patch('/loans/{loan}/return', [MemberLoanController::class, 'returnBook'])->name('loans.return');
    Route::patch('/loans/{loan}/renew', [MemberLoanController::class, 'renew'])->name('loans.renew');
    Route::get('/payments', [MemberFineController::class, 'index'])->name('payments.index');
    Route::post('/fines/{fine}/pay', [MemberFineController::class, 'pay'])->name('fines.pay');
});

require __DIR__.'/auth.php';