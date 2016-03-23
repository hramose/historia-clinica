@extends('layouts.layout')

@section('content')
    <div id="test" ng-controller="TestController">
        <ul id="lista-acciones">
            <li>
                <div>
                    <a href="#" ng-click="show_output('{{URL::to('api/birthday')}}', 'BirthdayCheck', 'days')">BirthdayCheck</a>
                    <input
                            type="text" ng-model="days">
                    <span>Días</span>
                </div>
            </li>
            <li>
                <div>
                    <a href="#" ng-click="show_output('{{URL::to('api/birthday-email')}}', 'BirthdayCheck with Email', 'daysMail')">BirthdayCheck with Email</a>
                    <input
                            type="text" ng-model="daysMail">
                    <span>Días</span>
                </div>
            </li>
        </ul>
        <div id="datos-output">
            <label>[[title]]</label><span ng-show="time == 0">[[timeLoader]]</span><span class="ms">[[time|number]] secs</span><span ng-cloak ng-show="status != 0" id="check">&check;</span>
        </div>
        <div id="output" ng-cloak>
            <span ng-bind-html="output"></span>
            <span ng-show="output == '' && !first_time" ng-cloak>
                No ha devuelto resultados...
            </span>
        </div>
    </div>
@endsection