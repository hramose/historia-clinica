<!doctype html>
<html lang="{{ $lang or 'ca' }}" ng-app="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestió pacients - {{ $title or 'HCaboSantos.cat' }}</title>
    @include('layouts.header')
</head>
<body @if (!Auth::check() || isset($email)) class="no-login" @endif ng-controller="AppController">
@if (Auth::check() && !isset($email))
    @if (session('isMobile'))
        @include('layouts.menu-mobile')
    @else
        @include('layouts.menu')
    @endif
@endif
<div class="wrap-content">
    <row id="main-page" {{--@if (!Auth::check())--}} centered {{--@endif--}}>
        <column cols="12">
            <row centered>
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
                </column>
            </row>
            <row id="layout12-main" around>
                @yield('content')
            </row>
        </column>
    </row>
</div>
<footer id="footer">
    <span class="left">© Helena Cabo Santos {{ date('Y') }}. All rights reserved.</span>
    @if (Auth::check())
        <span class="right"><a href="{{ URL::to('auth/logout') }}">{{ trans('messages.logout') }}</a></span>
    @endif
    <div class="clearfix"></div>
    <span class="right" ng-controller="AppController">Hora: <span ng-cloak class="actual-date">[[actual_date]]</span></span>
</footer>
@include('layouts.scripts')
</body>
</html>