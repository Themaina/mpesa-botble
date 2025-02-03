<?php

use Illuminate\Support\Facades\Route;
use Botble\Mpesa\Controllers\PaymentController;

Route::group(['prefix' => 'mpesa'], function () {
    // Route to initiate a payment
    Route::post('/initiate', [PaymentController::class, 'initiatePayment'])->name('mpesa.initiate');

    // Callback route for Daraja to notify payment status
    Route::post('/callback', [PaymentController::class, 'callback'])->name('mpesa.callback');
});
