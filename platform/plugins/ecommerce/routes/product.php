<?php

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/ecommerce', 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
            Route::resource('', 'ProductController')
                ->parameters(['' => 'product']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'ProductController@deletes',
                'permission' => 'products.destroy',
            ]);

            Route::post('add-attribute-to-product/{id}', [
                'as'         => 'add-attribute-to-product',
                'uses'       => 'ProductController@postAddAttributeToProduct',
                'permission' => 'products.edit',
            ]);

            Route::post('delete-version/{id}', [
                'as'         => 'delete-version',
                'uses'       => 'ProductController@deleteVersion',
                'permission' => 'products.edit',
            ]);

            Route::delete('items/delete-versions', [
                'as'         => 'delete-versions',
                'uses'       => 'ProductController@deleteVersions',
                'permission' => 'products.edit',
            ]);

            Route::post('add-version/{id}', [
                'as'         => 'add-version',
                'uses'       => 'ProductController@postAddVersion',
                'permission' => 'products.edit',
            ]);

            Route::get('get-version-form/{id?}', [
                'as'         => 'get-version-form',
                'uses'       => 'ProductController@getVersionForm',
                'permission' => 'products.edit',
            ]);

            Route::post('update-version/{id}', [
                'as'         => 'update-version',
                'uses'       => 'ProductController@postUpdateVersion',
                'permission' => 'products.edit',
            ]);

            Route::post('generate-all-version/{id}', [
                'as'         => 'generate-all-versions',
                'uses'       => 'ProductController@postGenerateAllVersions',
                'permission' => 'products.edit',
            ]);

            Route::post('store-related-attributes/{id}', [
                'as'         => 'store-related-attributes',
                'uses'       => 'ProductController@postStoreRelatedAttributes',
                'permission' => 'products.edit',
            ]);

            Route::post('save-all-version/{id}', [
                'as'         => 'save-all-versions',
                'uses'       => 'ProductController@postSaveAllVersions',
                'permission' => 'products.edit',
            ]);

            Route::get('get-list-product-for-search', [
                'as'         => 'get-list-product-for-search',
                'uses'       => 'ProductController@getListProductForSearch',
                'permission' => 'products.edit',
            ]);

            Route::get('get-relations-box/{id?}', [
                'as'         => 'get-relations-boxes',
                'uses'       => 'ProductController@getRelationBoxes',
                'permission' => 'products.edit',
            ]);

            Route::get('get-list-products-for-select', [
                'as'         => 'get-list-products-for-select',
                'uses'       => 'ProductController@getListProductForSelect',
                'permission' => 'products.index',
            ]);

            Route::post('create-product-when-creating-order', [
                'as'         => 'create-product-when-creating-order',
                'uses'       => 'ProductController@postCreateProductWhenCreatingOrder',
                'permission' => 'products.create',
            ]);

            Route::get('get-all-products-and-variations', [
                'as'         => 'get-all-products-and-variations',
                'uses'       => 'ProductController@getAllProductAndVariations',
                'permission' => 'products.index',
            ]);

            Route::post('update-order-by', [
                'as'         => 'update-order-by',
                'uses'       => 'ProductController@postUpdateOrderby',
                'permission' => 'products.edit',
            ]);
        });
    });
});
