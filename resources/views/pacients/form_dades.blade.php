@extends('layouts.layout')

@section('content')
    <div ng-controller="PacientsController">
        <div class="back-to-list">
            <a href="{{ URL::route('pacientsIndex') }}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> {{trans('messages.go_back_to_pacient_list')}}
            </a>
        </div>
        <div class="related-links">
            <p>
                <a target="_blank" href="{{URL::route('valoracions.pacient.show', $patient->id)}}">
                    <i class="fa fa-calendar-check-o"></i> {{ trans('messages.goto_review') }}
                </a>
            </p>
            <p>
                <a target="_blank" href="{{URL::route('curso.pacient.show', $patient->id)}}">
                    <i class="fa fa-list"></i> {{ trans('messages.goto_course') }}
                </a>
            </p>
        </div>
        {!! Form::model($patient, array('route' => array('pacientsDadesUpdate', $patient->id), 'class' => 'forms login-form', 'name' => 'form', 'novalidate' => '')) !!}
        <section>
            <label>{{ trans('models.Pacientname') }}</label>
            {!! Form::text('name', null, ['class'=> '', 'ng-model' => 'pacient.name', 'required' => '', ]) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientsurname') }}</label>
            {!! Form::text('surname', null, ['class'=> '', 'ng-model' => 'pacient.surname']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientlastname') }}</label>
            {!! Form::text('lastname', null, ['class'=> '', 'ng-model' => 'pacient.lastname']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientbirth_date') }}</label>
            {!! Form::text('birth_date', null, ['class'=> '', 'datetime' => 'dd/MM/yyyy', 'ng-model' => 'pacient.birth_date','ng-change' => 'putAgeFromDate(pacient.birth_date)']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientage') }}</label>
            {!! Form::text('age', null, ['class'=> '', 'ng-model' => 'pacient.age']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientnif') }}</label>
            {!! Form::text('nif', null, ['class'=> '', 'ng-model' => 'pacient.nif', 'required' => '']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientphone') }}</label>
            {!! Form::text('phone', null, ['class'=> '', 'ng-model' => 'pacient.phone']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientemail') }}</label>
            {!! Form::text('email', null, ['class'=> '', 'ng-model' => 'pacient.email']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientaddress') }}</label>
            {!! Form::text('address', null, ['class'=> '', 'ng-model' => 'pacient.address']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientcity') }}</label>
            {!! Form::text('city', null, ['class'=> '', 'ng-model' => 'pacient.city']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientpostal_code') }}</label>
            {!! Form::text('postal_code', null, ['class'=> '', 'ng-model' => 'pacient.postal_code']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientgender') }}</label>
            {!! Form::select('gender', ['male' => trans('models.male'), 'female' => trans('models.female')], null, ['class'=> 'select', 'ng-model' => 'pacient.gender', 'placeholder' => trans('models.select_gender')]) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientprofession') }}</label>
            {!! Form::text('profession', null, ['class'=> '', 'ng-model' => 'pacient.profession']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacienthobbies') }}</label>
            {!! Form::text('hobbies', null, ['class'=> '', 'ng-model' => 'pacient.hobbies']) !!}
        </section>
        <section>
            {!! Form::token() !!}
            {!! Form::button(trans('messages.update_patient', ['name' => 'pacient']),['type' => 'primary', 'ng-disabled' => 'form.$invalid']) !!}
        </section>
        {!! Form::close() !!}
        <div class="pacient_json" style="display: none">
            {!! $patient->toJson() !!}
        </div>
    </div>
@endsection