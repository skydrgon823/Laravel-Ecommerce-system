<?php

Route::group(['namespace' => 'Theme\Wowy\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('ajax/cart', 'WowyController@ajaxCart')
            ->name('public.ajax.cart');

        Route::get('ajax/products', 'WowyController@ajaxGetProducts')
            ->name('public.ajax.products');

        Route::get('ajax/product-categories/products', 'WowyController@ajaxGetProductsByCategoryId')
            ->name('public.ajax.product-category-products');

        Route::get('ajax/featured-products', 'WowyController@getFeaturedProducts')
            ->name('public.ajax.featured-products');

        Route::get('ajax/posts', 'WowyController@ajaxGetPosts')->name('public.ajax.posts');

        Route::get('ajax/featured-product-categories', 'WowyController@getFeaturedProductCategories')
            ->name('public.ajax.featured-product-categories');

        Route::get('ajax/featured-brands', 'WowyController@ajaxGetFeaturedBrands')
            ->name('public.ajax.featured-brands');

        Route::get('ajax/related-products/{id}', 'WowyController@ajaxGetRelatedProducts')
            ->name('public.ajax.related-products');

        Route::get('ajax/product-reviews/{id}', 'WowyController@ajaxGetProductReviews')
            ->name('public.ajax.product-reviews');

        Route::get('ajax/get-flash-sales', 'WowyController@ajaxGetFlashSales')
            ->name('public.ajax.get-flash-sales');

        Route::get('ajax/quick-view/{id}', 'WowyController@getQuickView')
            ->name('public.ajax.quick-view');
    });
});

Theme::routes();

Route::group(['namespace' => 'Theme\Wowy\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('/', 'WowyController@getIndex')
            ->name('public.index');

        Route::get('sitemap.xml', 'WowyController@getSiteMap')
            ->name('public.sitemap');

        Route::get('{slug?}' . config('core.base.general.public_single_ending_url'), 'WowyController@getView')
            ->name('public.single');
    });
});
