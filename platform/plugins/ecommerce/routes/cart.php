<?php

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('cart', [
            'as'   => 'public.cart',
            'uses' => 'PublicCartController@getView',
        ]);

        Route::post('cart/add-to-cart', [
            'as'   => 'public.cart.add-to-cart',
            'uses' => 'PublicCartController@store',
        ]);

        Route::post('cart/update', [
            'as'   => 'public.cart.update',
            'uses' => 'PublicCartController@postUpdate',
        ]);

        Route::get('cart/remove/{id}', [
            'as'   => 'public.cart.remove',
            'uses' => 'PublicCartController@getRemove',
        ]);

        Route::get('cart/destroy', [
            'as'   => 'public.cart.destroy',
            'uses' => 'PublicCartController@getDestroy',
        ]);
    });
});
