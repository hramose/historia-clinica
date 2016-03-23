@extends('layouts.layout')

@section('content')
    <div id="test" ng-controller="TestController">
        <ul id="lista-acciones">
            <li>
                <a href="#" ng-click="show_output('{{URL::to('test/birthday')}}', 'BirthdayCheck', 'days')">BirthdayCheck</a>
                <input
                        type="text" placeholder="Días vista a consultar cumpleaños" ng-model="days">
            </li>
        </ul>
        <div id="datos-output">
            <label>[[title]]</label><span class="ms">[[time|number]] secs</span><span ng-cloak ng-show="status != 0" id="check">&check;</span>
        </div>
        <div id="output" ng-cloak>
            <span ng-bind-html="output"></span>
            <span ng-show="output == '' && !first_time" ng-cloak>
                No ha devuelto resultados...
            </span>
        </div>
    </div>
@endsection