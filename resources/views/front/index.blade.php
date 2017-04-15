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
        <div class="clearfix"></div>
        <div class="box widget" id="stats">
            <div class="content show-stats">
                <div class="counter-box">
                    <a href="{{URL::route('birthdaysListNoCheck')}}">
                        <i class="fa fa-birthday-cake"></i>

                        <div class="statistics">
                            <h5>{{count($birthdays_wo_check)}} {{--<span>%</span>--}}</h5>

                            <div class="grow alert-msg" ng-mouseout="delete_tooltip()"
                                 ng-mouseover="show_birthdays($event)" data-json="{{json_encode($birthdays)}}">
                                <p>{{trans('messages.birthdays')}}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="box widget" id="stats">
            <div class="content show-stats">
                <div class="counter-box">
                    <a href="{{URL::route('birthdaysList')}}">
                        <i class="fa fa-birthday-cake"></i>

                        <div class="statistics">
                            <h5>{{count($birthdays)}} {{--<span>%</span>--}}</h5>

                            <div class="grow alert-msg" ng-mouseout="delete_tooltip()"
                                 ng-mouseover="show_birthdays($event)" data-json="{{json_encode($birthdays)}}">
                                <p>{{trans('messages.birthdaysreported')}}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        {{--<div class="box widget" id="stats">
            <div class="content show-stats">
                <div class="counter-box">
                    <i class="fa fa-calendar-check-o"></i>

                    <div class="statistics">
                        <h5>{{count($stats_reviews)}} --}}{{--<span>%</span>--}}{{--</h5>

                        <div class="grow success-msg">
                            <p>{{trans('messages.stats_reviews')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
    </div>
@endsection