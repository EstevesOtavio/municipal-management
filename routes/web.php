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

Route::get('/fix-password', function () {
    $user = \App\Models\User::where('email', 'admin@admin.com')->first();
    if (!$user) return 'Usuário Admin não encontrado!';

    $user->password = \Illuminate\Support\Facades\Hash::make('password');
    $user->save();

    return 'Senha corrigida com sucesso!';
});

require __DIR__.'/auth.php';
