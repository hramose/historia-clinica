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

elixir(function (mix) {
    mix.sass('app.scss');
    mix.styles(['kube.min.css', 'style.css', 'metismenu.css', 'animate.min.css'], 'public/css/desktop.css');
    mix.scripts(['app.js', 'index.js'], 'public/js/desktop.js')
});
