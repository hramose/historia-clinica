var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.sourcemaps = false;

elixir(function (mix) {
    mix.sass('app.scss', 'resources/assets/css/style.css')
        .styles(['kube.min.css', 'style.css', 'metismenu.css', 'animate.min.css'], 'public/css/desktop.css')
        .scripts(['app.js', 'index.js', 'moment-with-locales.js', 'metismenu.js', 'jquery.imagemapster.js', 'validnif.js'], 'public/js/desktop.js')
        .version(["public/css/desktop.css", "public/js/desktop.js"]);
});
