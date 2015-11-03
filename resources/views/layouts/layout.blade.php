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
<body>
@if (Auth::check() && !isset($email))
    @include('layouts.menu')
@endif
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
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                @endif
            @endforeach
        </div>
        <!-- end .flash-message -->
        @yield('content')
    </column>
    <row>
        <footer id="footer">

            <blocks cols="2">
                <div><p>© Helena Cabo Santos {{ date('Y') }}. All rights reserved.</p></div>
                @if (Auth::check())
                    <div class="text-right"><a href="{{ URL::to('auth/logout') }}">{{ trans('messages.logout') }}</a></div>
                @endif
            </blocks>
        </footer>
    </row>
</row>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
{!! Html::script('js/metismenu.js') !!}
{!! Html::script('js/index.js') !!}
</body>
</html>