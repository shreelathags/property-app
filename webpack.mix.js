const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/agent/create.js', 'public/js')
    .js('resources/js/agent/view.js', 'public/js')
    .js('resources/js/property/list.js', 'public/js')
    .js('resources/js/home/home.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
