<?php

declare(strict_types=1);

use App\Modules\Documents\Presentation\Http\Controllers\LetterController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/inbox', [LetterController::class, 'inbox'])->name('letters.inbox');
    Route::get('/letters/create', [LetterController::class, 'create'])->name('letters.create');
    Route::post('/letters', [LetterController::class, 'store'])->name('letters.store');
    Route::post('/letters/actions/{letterAction}', [LetterController::class, 'updateStatus'])->name('letters.updateStatus');
});
