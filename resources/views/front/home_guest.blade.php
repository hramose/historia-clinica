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
        <a href="{{route('homepage')}}"><img src="{{asset('img/logo.png')}}" alt=""></a>
    </section>
</header>
<article>
    <section>
        @if (!$foundPacient && (isset($check)))
            <h3>{{trans('messages.make_requests_session')}}</h3>
            {{Form::open(['route' => 'searchByDni', 'class' => 'forms', 'name' => 'form', 'novalidate' => ''])}}
            <div class="form-group">
                <label for="dni">{{trans('messages.search_by_dni')}}</label>
                <input validnif required ng-model="nif" type="text" name="dni" value="">
            </div>
            <input ng-disabled="form.$invalid" type="submit">
            {{Form::close()}}
        @elseif($foundPacient)
            <h3>{{trans('messages.are_you', ['name' => $patient->name])}}</h3>
            <div id="pacient-info">

            </div>
        @else
            <h3>{{trans('messages.no_patient')}}</h3>
            nombre,
            apellidos,
            telf,
            obsv
            {{Form::open(['route' => '', 'class' => 'forms', 'name' => 'form', 'novalidate' => ''])}}
            <input type="hidden" name="dni" value="{{$dni}}">
            {{Form::close()}}
        @endif
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