<?php

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'discounts', 'as' => 'discounts.'], function () {
            Route::resource('', 'DiscountController')->parameters(['' => 'discount'])->except(['edit', 'update']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'DiscountController@deletes',
                'permission' => 'discounts.destroy',
            ]);

            Route::post('generate-coupon', [
                'as'         => 'generate-coupon',
                'uses'       => 'DiscountController@postGenerateCoupon',
                'permission' => 'discounts.create',
            ]);
        });
    });
});

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::group(['prefix' => 'coupon', 'as' => 'public.coupon.'], function () {
            Route::post('apply', [
                'as'   => 'apply',
                'uses' => 'PublicCheckoutController@postApplyCoupon',
            ]);

            Route::post('remove', [
                'as'   => 'remove',
                'uses' => 'PublicCheckoutController@postRemoveCoupon',
            ]);
        });
    });
});
