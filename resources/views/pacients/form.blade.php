@extends('layouts.layout')

@section('content')
    <div ng-controller="PacientsController">
        {!! Form::open(['route' => 'pacientsNou', 'class' => 'forms login-form', 'name' => 'form', 'novalidate' => '']) !!}
        <section>
            <label>{{ trans('models.Pacientname') }}</label>
            {!! Form::input('text', 'name', '', ['class'=> '', 'ng-model' => 'pacient.name', 'required' => '']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientsurname') }}</label>
            {!! Form::input('text', 'surname', '', ['class'=> '', 'ng-model' => 'pacient.surname']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientlastname') }}</label>
            {!! Form::input('text', 'lastname', '', ['class'=> '', 'ng-model' => 'pacient.lastname']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientemail') }}</label>
            {!! Form::input('text', 'email', '', ['class'=> '', 'ng-model' => 'pacient.email']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientphone') }}</label>
            {!! Form::input('text', 'phone', '', ['class'=> '', 'ng-model' => 'pacient.phone']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientaddress') }}</label>
            {!! Form::input('text', 'address', '', ['class'=> '', 'ng-model' => 'pacient.address']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientcity') }}</label>
            {!! Form::input('text', 'city', '', ['class'=> '', 'ng-model' => 'pacient.city']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientpostal_code') }}</label>
            {!! Form::input('text', 'postal_code', '', ['class'=> '', 'ng-model' => 'pacient.postal_code']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientnif') }}</label>
            {!! Form::input('text', 'nif', '', ['class'=> '', 'ng-model' => 'pacient.nif', 'validnif' => '', 'required' => '']) !!}
            <span class="alert alert-success" ng-if="form.nif.$valid">DNI correcte</span>
        </section>
        <section>
            <label>{{ trans('models.Pacientgender') }}</label>
            {!! Form::select('gender', ['male' => trans('models.male'), 'female' => trans('models.female')], null, ['class'=> 'select', 'ng-model' => 'pacient.gender', 'placeholder' => trans('models.select_gender')]) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientbirth_date') }}</label>
            {!! Form::input('text', 'birth_date', '', ['class'=> '', 'ng-model' => 'pacient.birth_date', 'placeholder' => 'DD/MM/YYYY', 'ng-change' => 'putAgeFromDate(pacient.birth_date)']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientage') }}</label>
            {!! Form::input('number', 'age', '', ['class'=> '', 'ng-model' => 'pacient.age', 'readonly' => 'readonly']) !!}
        </section>
        <section>
            <label>{{ trans('models.Pacientprofession') }}</label>
            {!! Form::input('text', 'profession', '', ['class'=> '', 'ng-model' => 'pacient.profession']) !!}
        </section>

        <section>
            {!! Form::button(trans('messages.save', ['name' => 'pacient']),['type' => 'primary', 'ng-disabled' => 'form.$invalid']) !!}
        </section>
        {!! Form::close() !!}
    </div>
@endsection