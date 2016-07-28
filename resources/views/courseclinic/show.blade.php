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
                    </column>
                    <row>
                        <column cols="11">
                            {!! Form::model($clinicalCourse, ['route' => ['cursoGuarda', $pacient->id], 'class' => 'forms login-form', 'name' => 'formCclinic', 'novalidate' => '', 'ng-submit' => 'submit_form($event)']) !!}
                            <div id="cclinic" style="display: none">
                                {!! $clinicalCourse->toJson() !!}
                            </div>

                            <h4>{{trans('models.Cclinic_title')}}</h4>
                            <section>
                                <textarea name="cclinic[content]" ng-model="cclinic.content"></textarea>
                            </section>

                            <section class="send-button">
                                {!! Form::button(trans('messages.save_cclinic'),['type' => 'primary', 'ng-disabled' => 'formCclinic.$invalid']) !!}
                                @if ($clinicalCourse->id != '')
                                    {!! Form::button(trans('messages.delete_cclinic'),['type' => 'primary', 'class' => 'error-button', 'ng-click' => 'delete_cclinic($event)']) !!}
                                @endif
                            </section>
                            {!! Form::close() !!}
                        </column>
                    </row>
                </row>
            </div>
        </div>
    </column>

@endsection

