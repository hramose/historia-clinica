@extends('layouts.layout')

@section('content')
    <div ng-controller="PacientsController">
        {!! Form::model($patient, array('route' => array('pacientsDadesUpdate', $patient->id), 'class' => 'forms login-form')) !!}
            <section>
                <label>{{ trans('models.Pacientname') }}</label>
                {!! Form::text('name', null, ['class'=> '', 'required' => '', ]) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientsurname') }}</label>
                {!! Form::text('surname', null, ['class'=> '', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientlastname') }}</label>
                {!! Form::text('lastname', null, ['class'=> '', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientaddress') }}</label>
                {!! Form::text('address', null, ['class'=> '', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientnif') }}</label>
                {!! Form::text('nif', null, ['class'=> '', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientbirth_date') }}</label>
                {!! Form::text('birth_date', null, ['class'=> '', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientage') }}</label>
                {!! Form::text('age', null, ['class'=> '', 'required' => '']) !!}
            </section>
            <section>
                <label>{{ trans('models.Pacientprofession') }}</label>
                {!! Form::text('profession', null, ['class'=> '', 'required' => '']) !!}
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