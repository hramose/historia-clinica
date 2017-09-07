@extends('layouts.layout12')

@section('content')
    <column cols="11">
        <div id="cclinic-content" ng-controller="ClinicCourseController">
            <h2>Dades del pacient</h2>
            {!! Form::model($pacient, array('id' => 'reduced-pacient-date', 'class' => 'forms', 'name' => 'form')) !!}
            <div id="patient" style="display: none">
                {!! $pacient->toJson() !!}
            </div>
            <row>
                <column cols="4">
                    <section>
                        <label>{{ trans('models.Pacientid') }}</label>
                        <p>[[patient.id]]</p>
                        <label>{{ trans('models.Pacientname') }}</label>
                        <p>[[patient.full_name]]</p>
                        <label>{{ trans('models.Pacientpat_antecedents') }}</label>
                        [[patient.last_pathological_antecedents]]
                        <p>
                            <a target="_blank" href="{{URL::route('valoracions.pacient.show', '')}}/[[patient.id]]">
                                <i class="fa fa-calendar-check-o"></i>{{ trans('messages.goto_review') }}
                            </a>
                        </p>
                        <p>
                            <a target="_blank" href="{{URL::route('pacientsDades', '')}}/[[patient.id]]">
                                <i class="fa fa-user"></i>{{ trans('messages.goto_pacient_data') }}
                            </a>
                        </p>
                    </section>
                </column>
                <column cols="3">
                    <section>
                        <label>{{ trans('models.Pacientbirth_date') }}</label>
                        <p>[[patient.birth_date]]</p>
                        <label>{{ trans('models.Pacientage') }}</label>
                        <p>[[patient.age]]</p>
                    </section>
                </column>
                <column cols="5">
                    <hcs-calendar events="{{$clinicalCourses->toJson()}}"></hcs-calendar>
                </column>
            </row>
            {!! Form::close() !!}
            <div id="search-filters">
                <row>
                    <column cols="6"></column>
                    <column cols="6">
                        {!! Form::select('selected_cclinic', [-1 => trans('messages.empty_option_cclinic_dates')] + $clinicalCourses->lists('date', 'id')->toArray(), $clinicalCourse->id, ['onchange' => 'angular.element(this).scope().edit_cclinic(this)']) !!}
                        <a class="buttons" href="{{route('curso.pacient.show', ['patient' => $pacient])}}">
                            {{trans('messages.new_cclinic')}}
                        </a>
                    </column>
                    <row>
                        <column cols="11">
                            {{--*/
                                if ($clinicalCourse->exists) {
                                    $route = ['cursoActualiza', $pacient, $clinicalCourse];
                                } else {
                                    $route = ['cursoGuarda', $pacient];
                                }
                            /*--}}
                            {!! Form::model($clinicalCourse, ['route' => $route, 'class' => 'forms login-form', 'name' => 'formCclinic', 'novalidate' => '', 'ng-submit' => 'submit_form($event)']) !!}
                            <div id="cclinic" style="display: none">
                                {!! $clinicalCourse->toJson() !!}
                            </div>
                            @if($clinicalCourse->exists)
                                {{method_field('PUT')}}
                            @endif

                            <h4>{{trans('models.Cclinic_title')}}</h4>
                            <column cols="3">
                                <section>
                                    <input type="text" ng-model="cclinic.date" name="cclinic[date]" required
                                           ng-click="today_date($event)">
                                </section>
                            </column>
                            <section>
                                <label>{{ trans('models.Cclinic_visit_reason') }}</label>
                                <input type="text" required ng-model="cclinic.visit_reason"
                                       name="cclinic[visit_reason]">
                            </section>
                            <h4>{{trans('models.Cclinic_concepts')}}</h4>
                            <section>
                                <span id="bill-concepts" style="display: none">
                                    {{$billConcepts->toJson()}}
                                </span>
                                <div id="concept-tags">
                                    <label ng-repeat="concept in bill_concepts" ng-click="addToContent(concept)">
                                        [[concept.name]]
                                    </label>
                                </div>
                            </section>
                            <section>
                                <textarea name="cclinic[content]" required ng-model="cclinic.content"></textarea>
                            </section>

                            <section class="send-button">
                                {!! Form::button(trans('messages.save_cclinic'),['type' => 'primary', 'ng-disabled' => 'formCclinic.$invalid']) !!}
                                @if ($clinicalCourse->exists)
                                    {!! Form::button(trans('messages.delete_cclinic'),['form' => 'eliminarCurso', 'type' => 'primary', 'class' => 'error-button']) !!}
                                @endif
                            </section>
                            {!! Form::close() !!}
                            <form id="eliminarCurso" name="eliminarCurso" method="post"
                                  action="{{route('cursoElimina', ['patient' => $pacient, 'clinicalCourse' => $clinicalCourse])}}">
                                {{method_field('DELETE')}}
                                {{csrf_field()}}
                            </form>
                        </column>
                    </row>
                </row>
            </div>
        </div>
    </column>

@endsection

