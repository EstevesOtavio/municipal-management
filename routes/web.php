<?php

use App\Livewire\DashboardIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard', ['secretariat' => 'urbanismo']);
});

// Rota dinâmica: carrega o painel da secretaria informada na URL
Route::get('/painel/{secretariat:slug}', DashboardIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
