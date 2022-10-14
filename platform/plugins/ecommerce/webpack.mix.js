let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/plugins/' + directory;
const dist = 'public/vendor/core/plugins/' + directory;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js(source + '/resources/assets/js/discount.js', dist + '/js').vue({ version: 2 })
    .js(source + '/resources/assets/js/order-create.js', dist + '/js').vue({ version: 2 })
    .js(source + '/resources/assets/js/front/checkout.js', dist + '/js')
    .copy(dist + '/js/discount.js', source + '/public/js')
    .copy(dist + '/js/order-create.js', source + '/public/js')
    .copy(dist + '/js/checkout.js', source + '/public/js');

const scripts = [
    'edit-product.js',
    'ecommerce-product-attributes.js',
    'change-product-swatches.js',
    'currencies.js',
    'review.js',
    'shipping.js',
    'utilities.js',
    'payment-method.js',
    'customer.js',
    'setting.js',
    'order.js',
    'order-incomplete.js',
    'shipment.js',
    'store-locator.js',
    'report.js',
    'dashboard-widgets.js',
    'avatar.js',
    'flash-sale.js',
    'bulk-import.js',
    'export.js',
];

scripts.forEach(item => {
    mix
        .js(source + '/resources/assets/js/' + item, dist + '/js')
        .copy(dist + '/js/' + item, source + '/public/js');
});

const styles = [
    'ecommerce.scss',
    'ecommerce-product-attributes.scss',
    'currencies.scss',
    'review.scss',
    'customer.scss',
    'front-theme.scss',
    'front-theme-rtl.scss',
    'report.scss',
];

styles.forEach(item => {
    mix
        .sass(source + '/resources/assets/sass/' + item, dist + '/css')
        .copy(dist + '/css/' + item.replace('.scss', '.css'), source + '/public/css');
});
