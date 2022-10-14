<?php

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('compare', [
            'as'   => 'public.compare',
            'uses' => 'CompareController@index',
        ]);

        Route::post('compare/{productId}', [
            'as'   => 'public.compare.add',
            'uses' => 'CompareController@store',
        ]);

        Route::delete('compare/{productId}', [
            'as'   => 'public.compare.remove',
            'uses' => 'CompareController@destroy',
        ]);
    });
});
