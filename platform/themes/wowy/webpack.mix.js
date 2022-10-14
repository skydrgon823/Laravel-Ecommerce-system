let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/themes/' + directory;
const dist = 'public/themes/' + directory;

mix.js(source + '/assets/js/components.js', dist + '/js').vue({ version: 2 });

mix
    .sass(source + '/assets/sass/style.scss', dist + '/css')
    .sass(source + '/assets/sass/rtl.scss', dist + '/css')
    .js(source + '/assets/js/backend.js', dist + '/js')
    .js(source + '/assets/js/main.js', dist + '/js')
    .js(source + '/assets/js/icons-field.js', dist + '/js')

    .copy(dist + '/css/style.css', source + '/public/css')
    .copy(dist + '/css/rtl.css', source + '/public/css')
    .copy(dist + '/js/components.js', source + '/public/js')
    .copy(dist + '/js/backend.js', source + '/public/js')
    .copy(dist + '/js/main.js', source + '/public/js')
    .copy(dist + '/js/icons-field.js', source + '/public/js');
