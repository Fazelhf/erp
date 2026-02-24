<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LetterController; 
use App\Http\Controllers\HrController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// بخش داشبورد
Route::get('/dashboard', function () {
    $customersCount = \App\Models\Customer::where('is_active', true)->count();
    $productsCount = \App\Models\Product::where('is_active', true)->count();
    $invoicesCount = \App\Models\Invoice::count();
    $totalRevenue = \App\Models\Invoice::where('status', 'paid')->sum('total');
    $pendingInvoices = \App\Models\Invoice::whereIn('status', ['draft', 'sent'])->count();
    $recentInvoices = \App\Models\Invoice::with('customer')->latest()->take(5)->get();
    $lowStockProducts = \App\Models\Product::where('stock', '<', 10)->where('is_active', true)->count();
    
    $monthlyRevenue = \App\Models\Invoice::where('status', 'paid')
        ->whereYear('invoice_date', now()->year)
        ->whereMonth('invoice_date', now()->month)
        ->sum('total');
    
    return view('dashboard', compact(
        'customersCount', 
        'productsCount', 
        'invoicesCount', 
        'totalRevenue', 
        'pendingInvoices',
        'recentInvoices',
        'lowStockProducts',
        'monthlyRevenue'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// بخش‌هایی که نیاز به لاگین دارند
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::resource('invoices', InvoiceController::class);

    Route::get('/inbox', [LetterController::class, 'inbox'])->name('letters.inbox');
    Route::get('/letters/create', [LetterController::class, 'create'])->name('letters.create');
    Route::post('/letters/store', [LetterController::class, 'store'])->name('letters.store');
    Route::post('/letters/action/{id}', [LetterController::class, 'updateStatus'])->name('letters.updateStatus');
    Route::get('/hr/my-requests', [HrController::class, 'index'])->name('hr.index'); // این همان روتی است که خطا می‌داد
    Route::post('/hr/request/store', [HrController::class, 'store'])->name('hr.store');
    Route::get('/hr/admin/requests', [HrController::class, 'managerInbox'])->name('hr.admin.index');
    Route::post('/hr/admin/action/{id}', [HrController::class, 'updateStatus'])->name('hr.admin.update');
});

require __DIR__.'/auth.php';
