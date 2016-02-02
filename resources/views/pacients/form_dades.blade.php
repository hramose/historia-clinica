@extends('layouts.layout')

@section('content')
    <div ng-controller="PacientsController">
        {!! Form::model($patient, array('route' => array('pacientsDadesUpdate', $patient->id), 'class' => 'forms login-form', 'name' => 'form', 'novalidate' => '')) !!}
            <section>
                <label>{{ trans('models.Pacientname') }}</label>
                {!! Form::text('name', null, ['class'=> '', 'ng-model' => 'pacient.name', 'required' => '', ]) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientsurname') }}</label>
                {!! Form::text('surname', null, ['class'=> '', 'ng-model' => 'pacient.surname', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientlastname') }}</label>
                {!! Form::text('lastname', null, ['class'=> '', 'ng-model' => 'pacient.lastname', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientaddress') }}</label>
                {!! Form::text('address', null, ['class'=> '', 'ng-model' => 'pacient.address', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientnif') }}</label>
                {!! Form::text('nif', null, ['class'=> '', 'ng-model' => 'pacient.nif', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientbirth_date') }}</label>
                {!! Form::text('birth_date', null, ['class'=> '', 'ng-model' => 'pacient.birth_date',  'required' => '','ng-change' => 'putAgeFromDate(pacient.birth_date)']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientage') }}</label>
                {!! Form::text('age', null, ['class'=> '', 'ng-model' => 'pacient.age', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientprofession') }}</label>
                {!! Form::text('profession', null, ['class'=> '', 'ng-model' => 'pacient.profession', 'required' => '']) !!}
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