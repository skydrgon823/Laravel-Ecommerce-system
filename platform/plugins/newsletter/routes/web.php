<?php

Route::group(['namespace' => 'Botble\Newsletter\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'newsletters', 'as' => 'newsletter.'], function () {
            Route::resource('', 'NewsletterController')->only(['index', 'destroy'])->parameters(['' => 'newsletter']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'NewsletterController@deletes',
                'permission' => 'newsletter.destroy',
            ]);
        });
    });

    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::post('newsletter/subscribe', [
            'as'   => 'public.newsletter.subscribe',
            'uses' => 'PublicController@postSubscribe',
        ]);

        Route::get('newsletter/unsubscribe/{user}', [
            'as'   => 'public.newsletter.unsubscribe',
            'uses' => 'PublicController@getUnsubscribe',
        ]);
    });
});
