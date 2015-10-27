<!doctype html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestió pacients - {{ $title }}</title>
    <link rel="stylesheet" href="{{ URL::asset('/css/kube.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/style.css') }}">
</head>
<body>
<row id="main-page" centered>
    <column cols="4">
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
</row>
<footer id="footer">

    <blocks cols="2">
        <div><p>© Imperavi 2009-2015. All rights reserved.</p></div>
        @if (Auth::check())
            <div class="text-right"><a href="{{ URL::to('auth/logout') }}">{{ trans('messages.logout') }}</a></div>
        @endif
    </blocks>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
</body>
</html>