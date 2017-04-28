@extends('layouts.layout12')

@section('content')
    <column cols="11">
        <div id="cclinic-content" ng-controller="ClinicCourseController">
            <h2>Dades del pacient</h2>
            {!! Form::model($pacient, array('class' => 'forms', 'name' => 'form')) !!}
            <div id="patient" style="display: none">
                {!! $pacient->toJson() !!}
            </div>
            <row>
                <column cols="3">
                    <section>
                        <label>{{ trans('models.Pacientid') }}</label>
                        {!! Form::text('id', null, ['class'=> 'width-11', 'readonly' => 'readonly', 'ng-model' => 'patient.id']) !!}
                    </section>
                    <section>
                        <label>{{ trans('models.Pacientbirth_date') }}</label>
                        {!! Form::text('birth_date', null, ['class'=> 'width-11', 'readonly' => 'readonly', 'ng-model' => 'patient.birth_date']) !!}
                    </section>
                </column>
                <column cols="6">
                    <section>
                        <label>{{ trans('models.Pacientname') }}</label>
                        {!! Form::text('fullname', null, ['class'=> 'width-12', 'readonly' => 'readonly', 'ng-model' => 'patient.full_name']) !!}
                    </section>
                    <section>
                        <label>{{ trans('models.Pacientage') }}</label>
                        {!! Form::text('age', null, ['class'=> 'width-12', 'readonly' => 'readonly', 'ng-model' => 'patient.age']) !!}
                    </section>
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
                                    <input type="text" ng-model="cclinic.date" name="cclinic[date]" required ng-click="today_date($event)">
                                </section>
                            </column>
                            <section>
                                CONCEPTS
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
                            <form id="eliminarCurso" name="eliminarCurso" method="post" action="{{route('cursoElimina', ['patient' => $pacient, 'clinicalCourse' => $clinicalCourse])}}">
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

