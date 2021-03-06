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
        .sass('mobile.scss', 'resources/assets/css/mobile.css')
        .styles(['home.css', 'angular-datepicker.min.css'], 'public/css/home.css')
        .styles(['kube.min.css', 'mobile.css', 'metismenu.css'], 'public/css/mobile.css')
        .styles(['kube.min.css', 'style.css', 'metismenu.css', 'animate.min.css', './public/bower_components/pickadate/lib/themes/default.css', './public/bower_components/pickadate/lib/themes/default.date.css'], 'public/css/desktop.css')
        .scripts(['jquery.min.js', 'app.js', 'index.js', 'moment-with-locales.js', 'metismenu.js', 'jquery.imagemapster.js', 'validnif.js', 'directives.js', './public/bower_components/angular-i18n/angular-locale_ca-es.js', './public/bower_components/pickadate/lib/picker.js', './public/bower_components/pickadate/lib/picker.date.js', './public/bower_components/pickadate/lib/picker.time.js', './public/bower_components/pickadate/lib/translations/ca_ES.js', 'modal.js'], 'public/js/desktop.js')
        .scripts(['jquery.min.js', 'moment-with-locales.js', './public/bower_components/angular-translate/angular-translate.min.js', 'angular-datepicker.min.js', 'home.js', 'validnif.js', './public/bower_components/angular-i18n/angular-locale_ca-es.js'], 'public/js/home.js')
        .version(["public/css/desktop.css", "public/css/home.css", "public/css/mobile.css", "public/js/desktop.js", "public/js/home.js"]);
});