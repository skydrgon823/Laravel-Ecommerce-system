<?php

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group([
            'prefix'     => 'shipping-methods',
            'permission' => 'shipping_methods.index',
            'as'         => 'shipping_methods.',
        ], function () {
            Route::get('', [
                'as'   => 'index',
                'uses' => 'ShippingMethodController@index',
            ]);

            Route::post('region/create', [
                'as'   => 'region.create',
                'uses' => 'ShippingMethodController@postCreateRegion',
            ]);

            Route::delete('region/delete', [
                'as'   => 'region.destroy',
                'uses' => 'ShippingMethodController@deleteRegion',
            ]);

            Route::delete('region/rule/delete', [
                'as'   => 'region.rule.destroy',
                'uses' => 'ShippingMethodController@deleteRegionRule',
            ]);

            Route::put('region/rule/update/{id}', [
                'as'   => 'region.rule.update',
                'uses' => 'ShippingMethodController@putUpdateRule',
            ]);

            Route::post('region/rule/create', [
                'as'   => 'region.rule.create',
                'uses' => 'ShippingMethodController@postCreateRule',
            ]);
        });
    });
});
