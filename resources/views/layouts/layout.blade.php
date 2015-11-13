<!doctype html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestió pacients - {{ $title }}</title>
    <link rel="stylesheet" href="{{ URL::asset('/css/kube.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/metismenu.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body @if (!Auth::check()) class="no-login" @endif>
@if (Auth::check() && !isset($email))
    @include('layouts.menu')
@endif
<div class="wrap-content">
    <row id="main-page" {{--@if (!Auth::check())--}} centered {{--@endif--}}>
        <column cols="6">
            Lorem fistrum torpedo a wan diodeno no te digo trigo por no llamarte Rodrigor mamaar hasta luego Lucas. Sexuarl por la gloria de mi madre se calle ustée jarl tiene musho peligro papaar papaar la caidita no puedor ese hombree sexuarl a gramenawer. Fistro tiene musho peligro amatomaa benemeritaar mamaar a wan está la cosa muy malar qué dise usteer se calle ustée fistro. A wan sexuarl llevame al sircoo tiene musho peligro por la gloria de mi madre. Jarl me cago en tus muelas pupita sexuarl tiene musho peligro mamaar sexuarl. Quietooor sexuarl te va a hasé pupitaa me cago en tus muelas amatomaa amatomaa. Te voy a borrar el cerito te voy a borrar el cerito quietooor fistro no puedor papaar papaar por la gloria de mi madre caballo blanco caballo negroorl ese pedazo de jarl no te digo trigo por no llamarte Rodrigor.
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
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
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
{!! Html::script('js/metismenu.js') !!}
{!! Html::script('js/index.js') !!}
</body>
</html>