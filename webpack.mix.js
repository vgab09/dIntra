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
mix.webpackConfig({devtool: "source-map"});

mix.scripts([
    'node_modules/datatables.net/js/jquery.dataTables.min.js',
    'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
    'resources/js/datatable.js'
], 'public/js/datatables.js');

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/popper.js/dist/umd/popper.min.js',
    'node_modules/chosen-js/chosen.jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/moment/min/moment.min.js',
    'node_modules/moment-range/dist/moment-range.js',
    'node_modules/moment/locale/hu.js',
    'resources/js/moment-ext.js'
], 'public/js/vendor.js');

mix.scripts([
    'node_modules/@fullcalendar/core/main.min.js',
    'node_modules/@fullcalendar/list/main.min.js',
    'node_modules/@fullcalendar/daygrid/main.min.js',
    'node_modules/@fullcalendar/core/locales/hu.js'

], 'public/js/fullcalendar.js');

mix.scripts([
    'node_modules/daterangepicker/daterangepicker.js',
], 'public/js/daterangepicker.js');

mix.scripts('resources/js/app.js', 'public/js/app.js');

mix.sass('node_modules/bootstrap/scss/bootstrap.scss', 'public/css')
    .sass('resources/sass/fontawesome.scss', 'public/css')
    .sass('resources/sass/style.scss', 'public/css')
    .sass('resources/sass/datatables.scss', 'public/css').sourceMaps();

mix.copy('node_modules/chosen-js/chosen.min.css', 'public/css/chosen.min.css');

mix.styles([
    'node_modules/@fullcalendar/core/main.min.css',
    'node_modules/@fullcalendar/list/main.min.css',
    'node_modules/@fullcalendar/daygrid/main.min.css'
], 'public/css/fullcalendar.min.css');

mix.styles('node_modules/daterangepicker/daterangepicker.css', 'public/css/darerangepicker.min.css');