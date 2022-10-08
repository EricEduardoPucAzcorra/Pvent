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
//para javascript
mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/proyecto.js', 'public/js/proyecto.js')
    .scripts(['resources/js/bootstrap.min.js',
        'resources/js/Chart.min.js',
        'resources/js/jquery.min.js',
        'resources/js/pace.min.js',
        'resources/js/popper.min.js', 
        'resources/js/template.js'], 'public/js/plantilla.js')
    .vue()
    .styles(['resources/css/font-awesome.min.css',
    'resources/css/simple-line-icons.min.css', 
    'resources/css/style.css'], 'public/css/plantilla.css');

    // para css
