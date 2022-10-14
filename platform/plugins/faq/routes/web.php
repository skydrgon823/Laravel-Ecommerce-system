<?php

Route::group(['namespace' => 'Botble\Faq\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'faq-categories', 'as' => 'faq_category.'], function () {
            Route::resource('', 'FaqCategoryController')->parameters(['' => 'faq_category']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FaqCategoryController@deletes',
                'permission' => 'faq_category.destroy',
            ]);
        });

        Route::group(['prefix' => 'faqs', 'as' => 'faq.'], function () {
            Route::resource('', 'FaqController')->parameters(['' => 'faq']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FaqController@deletes',
                'permission' => 'faq.destroy',
            ]);
        });
    });
});
