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

            <div ng-cloak ng-show="!(patient | isEmpty)" id="datos-paciente">
                <row>
                    <column cols="6">
                        <section>
                            <label>{{ trans('models.Pacientid') }}</label>

                            <p>[[patient.id]]</p>
                        </section>
                        <section>
                            <label>{{ trans('models.Pacientbirth_date') }}</label>

                            <p>[[patient.birth_date]]</p>
                        </section>
                    </column>
                    <column cols="6">
                        <section>
                            <label>{{ trans('models.Pacientname') }}</label>

                            <p>[[patient.full_name]]</p>
                        </section>
                        <section>
                            <label>{{ trans('models.Pacientage') }}</label>

                            <p>[[patient.age]]</p>
                        </section>
                    </column>
                </row>
            </div>
            <div ng-cloak id="lista-cursos">
                <!-- lista cursos clinicos !-->
                <ul class="list-flat">
                    <li ng-repeat="cc in cclinics" ng-click="collapse_content(cc)">
                        [[cc.date]]
                        <div class="content-cc collapse" ng-class="{'in' : !show[[cc.id]], 'out' : show[[cc.id]]}">
                            <span ng-bind-html="cc.content | html"></span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection