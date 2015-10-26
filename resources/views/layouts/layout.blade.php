<!doctype html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <title>Gestió pacients - {{ $title }}</title>
    {{--<link rel="stylesheet" href="{{ URL::asset('/css/style.css') }}">--}}
</head>
<body>
    <div id="contenido">
        @yield('content')
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
</body>
</html>