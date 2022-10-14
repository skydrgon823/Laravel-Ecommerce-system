<?php

use App\Http\Middleware\VerifyCsrfToken;

Route::group(['namespace' => 'Botble\Mollie\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::post('mollie/payment/callback', [
        'as'   => 'mollie.payment.callback',
        'uses' => 'MollieController@paymentCallback',
    ])->withoutMiddleware(VerifyCsrfToken::class);
});
