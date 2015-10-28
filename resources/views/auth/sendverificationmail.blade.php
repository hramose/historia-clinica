@extends('layouts.layout')

@section('content')

    {!! Form::open(['route' => 'sendverificationmail', 'class' => 'forms register-form']) !!}

    <section>
        <label>{{ trans('models.Useremail') }}</label>
        {!! Form::email('email', '', ['class'=> '']) !!}
    </section>

    <section>
        {!! Form::button(trans('messages.send'),['type' => 'primary']) !!}
    </section>

    {!! Form::close() !!}

@endsection