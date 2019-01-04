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

mix.js('resources/js/app.js', 'public/js').sourceMaps()
.js('resources/js/datatable.js', 'public/js').sourceMaps();
mix.sass('resources/sass/style.scss', 'public/css').sourceMaps()
.sass('resources/sass/bootsrap.scss', 'public/css').sourceMaps()
.sass('resources/sass/fontawesome.scss', 'public/css').sourceMaps()
.sass('resources/sass/datatables.scss', 'public/css').sourceMaps();