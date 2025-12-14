<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaportController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RaportAcknowledgmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->hasRole('admin') || $user->hasRole('guru')) {
        return redirect('/admin');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ROUTE GRUP (Harus Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk mencetak raport siswa
    Route::get('/print-raport', [RaportController::class, 'print'])->name('raport.print');
});

require __DIR__.'/auth.php';

Route::post('/upload-raport', [RaportAcknowledgmentController::class, 'store'])->name('raport.upload');