@extends('layouts.layout')

@section('content')
    <div id="cc-all" ng-controller="ClinicCourseController">
        <span ng-init="url = '{{URL::route('cursoGetPacients', ['term' => ''])}}'"></span>

        <div id="cclinic-search">
            <div class="title-search">
                <h3>Cerca</h3>
            </div>
            <form action="#" novalidate name="formCclinic">
                <section>
                    <input ng-keyup="search_patient_cc($event)" ng-model="term" type="text"
                           placeholder="{{trans('messages.search_patient')}}">

                    <div ng-cloak ng-show="autocomplete" class="autocomplete">
                        <ul>
                            <li ng-repeat="p in pacients" ng-click="show_cclinic(p)">
                                <span ng-bind-html='underline_word(p.full_name)'></span>
                            </li>
                        </ul>
                    </div>
                </section>
            </form>

        </div>
        <div id="cclinic-patient-content">
            <span ng-cloak ng-show="term != ''" class="progress"></span>

            <div ng-cloak>
                <!-- lista cursos clinicos !-->
            </div>
        </div>
    </div>
@endsection