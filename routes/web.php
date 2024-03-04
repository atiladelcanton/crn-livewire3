<?php

use App\Livewire\Auth\{Livewire\Recovery, Login, Logout, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('auth.register');
Route::get('/logout', Logout::class)->name('logout');
Route::get('/password/recovery', Recovery::class)->name('password.recovery');
Route::get('/password/reset', fn () => 'oi')->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');
});
