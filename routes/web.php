<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});Route::get('/', [SubscriptionController::class, 'index']);
Route::post('/subscriptions', [SubscriptionController::class, 'store'])
    ->name('subscriptions.store');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/advertisements/{ad}', [DashboardController::class, 'show'])
    ->name('dashboard.advertisements.show');
Route::get('/verify-email/{token}', [SubscriptionController::class, 'verifyEmail']);

