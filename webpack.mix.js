const mix = require('laravel-mix');

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
mix.options({ manifest: false });

mix.browserSync('localhost:8001');

mix.js('resources/js/alpine.js', 'public/plugins/alpine/alpine.js')
    .postCss('resources/css/bootstrap-icons.css', 'public/plugins/bootstrap-icons/bootstrap-icons.css');
