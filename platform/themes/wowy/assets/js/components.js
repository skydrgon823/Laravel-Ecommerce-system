/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';
import FeaturedProductsComponent from './components/FeaturedProductsComponent';
import FeaturedNewsComponent from './components/FeaturedNewsComponent';
import RelatedProductsComponent from './components/RelatedProductsComponent';
import ProductReviewsComponent from './components/ProductReviewsComponent';
import FlashSaleProductsComponent from './components/FlashSaleProductsComponent';
import FeaturedProductCategoriesComponent from './components/FeaturedProductCategoriesComponent';
import ProductCollectionsComponent from './components/ProductCollectionsComponent';
import ProductCategoryProductsComponent from './components/ProductCategoryProductsComponent';
import FeaturedBrandsComponent from './components/FeaturedBrandsComponent';

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('featured-products-component', FeaturedProductsComponent);
Vue.component('related-products-component', RelatedProductsComponent);
Vue.component('featured-news-component', FeaturedNewsComponent);
Vue.component('product-reviews-component', ProductReviewsComponent);
Vue.component('flash-sale-products-component', FlashSaleProductsComponent);
Vue.component('featured-product-categories-component', FeaturedProductCategoriesComponent);
Vue.component('product-collections-component', ProductCollectionsComponent);
Vue.component('product-category-products-component', ProductCategoryProductsComponent);
Vue.component('featured-brands-component', FeaturedBrandsComponent);

/**
 * This let us access the `__` method for localization in VueJS templates
 * ({{ __('key') }})
 */
Vue.prototype.__ = key => {
    window.trans = window.trans || {};

    return window.trans[key] !== 'undefined' && window.trans[key] ? window.trans[key] : key;
};

const elements = [
    '#main-section',
];

elements.forEach(function (element) {
    if ($(element).length) {
        new Vue({
            el: element
        });
    }
});

Vue.directive('carousel', {
    inserted: function (element) {
        const el = $(element).find('.carousel-slider-wrapper');

        const id = el.attr('id');
        const sliderID = '#' + id;
        const appendArrowsClassName = '#' + id + '-arrows'

        $(sliderID).slick({
            dots: false,
            infinite: true,
            rtl: $('body').prop('dir') === 'rtl',
            speed: 1000,
            arrows: true,
            autoplay: false,
            slidesToShow: 6,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: false,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ],
            prevArrow: '<span class="slider-btn slider-prev"><i class="far fa-chevron-left"></i></span>',
            nextArrow: '<span class="slider-btn slider-next"><i class="far fa-chevron-right"></i></span>',
            appendArrows: appendArrowsClassName,
        });
    },
});
