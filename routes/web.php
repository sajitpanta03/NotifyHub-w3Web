<?php

use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\EmailLogController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    Route::get('/emails/create/{id?}', [EmailController::class, 'create'])
        ->name('emails.create');
    Route::post('/emails', [EmailController::class, 'store'])->name('emails.store');

    Route::get('/email-logs', [EmailLogController::class, 'show'])
        ->name('email-logs.show');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
