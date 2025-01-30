<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;

Route::get('/', function () {
    return response()->json(['laravel' => app()->version()]);
});


// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::get('/dashboard', Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';
