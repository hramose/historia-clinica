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
    <div id="language-selector">
        <a href="#ca" class="active" id="ca" title="CA" ng-click="change_language($event, 'ca')">
            <img src="{{asset('img/ca.png')}}" alt="CA">
        </a>
        <a href="#es" title="ES" id="es" ng-click="change_language($event, 'es')">
            <img src="{{asset('img/es.png')}}" alt="ES">
        </a>
    </div>
    <section>
        @if (!$foundPacient && (isset($check)))
            <h3>[[ 'REQUEST' | translate ]]</h3>
            {{Form::open(['route' => 'searchByDni', 'class' => 'forms', 'name' => 'form', 'novalidate' => ''])}}
            <div class="form-group">
                <label for="dni">[[ 'SEARCH' | translate ]]</label>
                <input validnif required ng-model="nif" type="text" name="dni" value=""/>
            </div>
            <input ng-disabled="form.$invalid" type="submit">
            {{Form::close()}}
        @elseif($foundPacient && !$mailSend)
            <h3>{{trans('messages.are_you', ['name' => $patient->name])}}</h3>
            <p>[[ 'CALENDAR' | translate]]</p>
            {{Form::open(['route' => 'requestNewVisit', 'class' => 'forms', 'name' => 'form', 'novalidate' => ''])}}
            <div class="form-group">
                <label for="name">[[ 'REQUESTDAY' | translate ]]</label>
                <input required ng-model="visit_request.day" type="text" name="day"/>
            </div>
            {{Form::close()}}
        @elseif($mailSend && !$foundPacient)
            <p>Mail enviado corectamente</p>
        @else
            <h3>[[ 'DATA' | translate ]]</h3>
            {{Form::open(['route' => 'requestsNewPatient', 'class' => 'forms', 'name' => 'form', 'novalidate' => ''])}}
            <input type="hidden" name="dni" value="{{$dni}}">
            <div class="form-group">
                <label for="name">[[ 'PACIENTNAME' | translate ]]</label>
                <input required ng-model="patient.full_name" type="text" name="name"/>
            </div>
            <div class="form-group">
                <label for="name">[[ 'PACIENTSURNAMES' | translate ]]</label>
                <input required ng-model="patient.surnames" type="text" name="surnames"/>
            </div>
            <div class="form-group">
                <label for="name">[[ 'PACIENTPHONE' | translate ]]</label>
                <input required ng-model="patient.phone" type="text" name="phone"/>
            </div>
            <div class="form-group">
                <label for="name">[[ 'OBSV' | translate ]]</label>
                <textarea ng-model="patient.obsv" type="text" name="obsv"></textarea>
            </div>
            <input ng-disabled="form.$invalid" type="submit">
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