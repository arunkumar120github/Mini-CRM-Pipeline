<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PipelineBoard;


Route::get('/', function () {
    return redirect()->route('login');
});


// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', PipelineBoard::class)->name('dashboard');
});

require __DIR__.'/auth.php';
