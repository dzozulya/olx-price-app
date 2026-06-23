<?php

use App\Http\Controllers\Web\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});Route::get('/', [SubscriptionController::class, 'index']);
Route::post('/subscriptions', [SubscriptionController::class, 'store'])
    ->name('subscriptions.store');
