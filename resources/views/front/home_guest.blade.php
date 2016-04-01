<!doctype html>
<html lang="{{ $lang or 'ca' }}" ng-app="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HCaboSantos - {{ $title or 'Home' }}</title>
    <base href="{{ URL::to('/') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset(elixir('css/home.css')) }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script>
        var base_url = "{{ URL::to('/') }}";
    </script>
</head>
<body ng-controller="RequestsController">
<header>
    <section>
        <img src="{{asset('img/logo.png')}}" alt="">
    </section>
</header>
<article>
    <section>
        {{Form::open(['route' => 'searchByDni', 'class' => 'forms', 'name' => 'form', 'novalidate' => ''])}}
        <div class="form-group">
            <label for="dni">{{trans('messages.search_by_dni')}}</label>
            <input type="text" name="dni" value="">
        </div>
        {{Form::close()}}
    </section>
</article>
<footer>
    <section>
        <span class="left">© Helena Cabo Santos {{ date('Y') }}. All rights reserved.</span>
    </section>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
<script src="{{asset(elixir('js/home.js'))}}"></script>
</body>
</html>