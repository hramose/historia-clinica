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
        .sass('home.scss', 'resources/assets/css/home.css')
        .styles('home.css', 'public/css/home.css')
        .styles(['kube.min.css', 'style.css', 'metismenu.css', 'animate.min.css'], 'public/css/desktop.css')
        .scripts(['jquery.min.js', 'app.js', 'index.js', 'moment-with-locales.js', 'metismenu.js', 'jquery.imagemapster.js', 'validnif.js',], 'public/js/desktop.js')
        .scripts('home.js', 'public/js/home.js')
        .version(["public/css/desktop.css", "public/css/home.css", "public/js/desktop.js", "public/js/home.js"]);
});
