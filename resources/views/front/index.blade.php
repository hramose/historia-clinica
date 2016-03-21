@extends('layouts.layout12')

@section('content')
    <div id="grid-shortcuts" ng-controller="FrontController">
        <div class="box" id="patient-search">
            <h3>{{trans('messages.patient_seeker')}}</h3>

            <div class="content">
                <input type="text" ng-keyup="search_pacients()" placeholder="Cercador de pacients..." name="term"
                       ng-model="patient">

                <div ng-show="patients.length > 0" class="autocomplete">
                    <ul>
                        <li ng-repeat="p in patients">
                            <div>
                                <span ng-bind-html='underline_word(p.full_name)'></span>
                                <span class="actions">
                                    <a href="{{URL::route('pacientsDades', '')}}/[[p.id]]"><i
                                                class="fa fa-user"></i></a>
                                    <a href="{{URL::route('valoracions.pacient.show', '')}}/[[p.id]]"><i
                                                class="fa fa-calendar-check-o"></i></a>
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection