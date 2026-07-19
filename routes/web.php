<?php
// routes/web.php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\FineController as AdminFineController; // ← TAMBAH INI

use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\Member\CatalogController;
use App\Http\Controllers\Member\LoanController as MemberLoanController;
use App\Http\Controllers\Member\FineController as MemberFineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Member\MemberCardController;
use App\Http\Controllers\PaymentController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/katalog', function () {
    return view('pages.catalog', [
        'books'      => \App\Models\Book::with('category')->latest()->get(),
        'categories' => \App\Models\Category::pluck('name'),
    ]);
})->name('catalog.public');

Route::get('/katalog/{book}', function (\App\Models\Book $book) {
    return view('pages.catalog-show', [
        'book' => $book->load('category'),
    ]);
})->name('catalog.show');

Route::get('/tentang', function () { return view('pages.about'); })->name('about');
Route::get('/bantuan', function () { return view('pages.help'); })->name('bantuan');

/*
|--------------------------------------------------------------------------
| Midtrans Webhook — HARUS di luar CSRF middleware
|--------------------------------------------------------------------------
*/

Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->name('payment.notification');

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Members
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::patch('/members/{member}/status', [MemberController::class, 'updateStatus'])->name('members.updateStatus');
    Route::patch('/members/{member}/role', [MemberController::class, 'updateRole'])->name('members.updateRole');
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');

    // Books
    Route::resource('books', BookController::class)->except(['show']);

    // Loans
    Route::get('/loans', [AdminLoanController::class, 'index'])->name('loans.index');
    Route::patch('/loans/{loan}/approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
    Route::patch('/loans/{loan}/decline', [AdminLoanController::class, 'decline'])->name('loans.decline');
    Route::patch('/loans/{loan}/return', [AdminLoanController::class, 'markReturned'])->name('loans.return');

    // Fines — Admin kelola denda + approve tunai ← BARU
    Route::get('/fines', [AdminFineController::class, 'index'])->name('fines.index');
    Route::patch('/fines/{fine}/mark-paid', [AdminFineController::class, 'markPaid'])->name('fines.markPaid');
    Route::get('/payments', [AdminFineController::class, 'payments'])->name('fines.payments');

    // Reports
    Route::get('/reports', function (\Illuminate\Http\Request $request) {
        $from = $request->filled('from') ? \Carbon\Carbon::parse($request->from) : now()->startOfMonth();
        $to   = $request->filled('to')   ? \Carbon\Carbon::parse($request->to)   : now()->endOfMonth();

        $transactions = \App\Models\Loan::with('user', 'book', 'fine')
            ->whereBetween('borrowed_at', [$from, $to])
            ->latest('borrowed_at')
            ->paginate(10);

        $chartLabels = [];
        $chartData   = [];
        $cursor      = $from->copy()->startOfMonth();
        $monthDiff   = min($cursor->diffInMonths($to->copy()->startOfMonth()), 11);

        for ($i = 0; $i <= $monthDiff; $i++) {
            $month          = $cursor->copy()->addMonths($i);
            $chartLabels[]  = $month->translatedFormat('M Y');
            $chartData[]    = \App\Models\Loan::whereYear('borrowed_at', $month->year)
                                ->whereMonth('borrowed_at', $month->month)
                                ->count();
        }

        return view('admin.report', [
            'totalBooks'          => \App\Models\Book::count(),
            'activeMembers'       => \App\Models\User::where('role', 'member')->where('status', 'active')->count(),
            'totalLoans'          => \App\Models\Loan::whereBetween('borrowed_at', [$from, $to])->count(),
            'totalFinesCollected' => \App\Models\Fine::where('status', 'paid')->whereBetween('updated_at', [$from, $to])->sum('amount'),
            'chartLabels'         => $chartLabels,
            'chartData'           => $chartData,
            'transactions'        => $transactions,
            'from'                => $from,
            'to'                  => $to,
        ]);
    })->name('reports.index');

    Route::get('/reports/export', function () {
        return back()->with('error', 'Fitur export belum tersedia.');
    })->name('reports.export');
});

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:member', 'verified'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {

    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');

    Route::post('/catalog/{book}/loan', [MemberLoanController::class, 'store'])->name('loans.store');
    Route::get('/loans', [MemberLoanController::class, 'index'])->name('loans.index');
    Route::patch('/loans/{loan}/return', [MemberLoanController::class, 'returnBook'])->name('loans.return');
    Route::patch('/loans/{loan}/renew', [MemberLoanController::class, 'renew'])->name('loans.renew');
    Route::get('/loans/history', [MemberLoanController::class, 'history'])->name('loans.history');

    // Fines & Payments
    Route::get('/payments', [MemberFineController::class, 'index'])->name('payments.index');
    Route::get('/fines/{fine}/pay', [MemberFineController::class, 'pay'])->name('fines.pay');

    Route::get('/card', [MemberCardController::class, 'show'])->name('card');
    Route::get('/settings', [MemberDashboardController::class, 'settings'])->name('settings');
    Route::get('/help', [MemberDashboardController::class, 'help'])->name('help');
});

/*
|--------------------------------------------------------------------------
| Midtrans Checkout — auth tapi di luar group member
| (karena PaymentController bukan di namespace Member)
|--------------------------------------------------------------------------
*/

Route::get('/payment/{fine}/checkout', [PaymentController::class, 'checkout'])
    ->middleware(['auth'])
    ->name('payment.checkout');

require __DIR__.'/auth.php';