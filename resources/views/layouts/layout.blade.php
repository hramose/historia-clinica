<!doctype html>
<html lang="{{ $lang or "ca" }}" ng-app="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestió pacients - {{ $title or "" }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/kube.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/metismenu.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/bower_components/angularPrint/angularPrint.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script>
        var base_url = "{{ URL::to('/') }}";
    </script>
</head>
<body @if (!Auth::check() || isset($email)) class="no-login" @endif ng-controller="AppController">
@if (Auth::check() && !isset($email))
    @include('layouts.menu')
@endif
<div class="wrap-content">
    <row id="main-page" {{--@if (!Auth::check())--}} centered {{--@endif--}}>
        <column cols="6">
            @if (Session::has('errors'))
                <div class="alert alert-warning" role="alert">
                    <ul>
                        <strong>{{ trans('messages.error_resumee') }}: </strong>
                        @foreach ($errors->all() as $error)
                            <li>{!!  $error !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('alert'))
                <div class="flash-message" ng-controller="FlashController">
                    <div ng-show="!timeOut"
                         class="alert alert-{{ Session::get('status') }}">{{ Session::get('alert') }}</div>
                </div>
                <!-- end .flash-message -->
            @endif
            @yield('content')
        </column>
    </row>
</div>
<footer id="footer">
    <span class="left">© Helena Cabo Santos {{ date('Y') }}. All rights reserved.</span>
    @if (Auth::check())
        <span class="right"><a href="{{ URL::to('auth/logout') }}">{{ trans('messages.logout') }}</a></span>
    @endif
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
{!! Html::script('/bower_components/angular-i18n/angular-locale_ca-es.js') !!}
{!! Html::script('/bower_components/angularPrint/angularPrint.js') !!}
{!! Html::script('js/metismenu.js') !!}
{!! Html::script('js/index.js') !!}
{!! Html::script('js/app.js') !!}
{!! Html::script('js/directives/validnif.js') !!}
{!! Html::script('js/moment-with-locales.js') !!}
</body>
</html>