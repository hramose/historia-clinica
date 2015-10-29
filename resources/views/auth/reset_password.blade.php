@extends('layouts.layout')

@section('content')
    {!! Form::open(['route' => 'reset_password', 'class' => 'forms register-form']) !!}

    @if (!isset($email))
        <section>
            <label>{{ trans('models.Useremail') }}</label>
            {!! Form::email('email', '', ['class'=> '']) !!}
        </section>
    @endif

    <section>
        <label>{{ trans('models.Userpassword') }}</label>
        {!! Form::password('password', ['class'=> '']) !!}
    </section>

    <section>
        <label>{{ trans('models.Userconfirmationpassword') }}</label>
        {!! Form::password('password_confirmation', ['class'=> '']) !!}
    </section>

    <section>
        {!! Form::button(trans('messages.send'),['type' => 'primary']) !!}
    </section>
    {!! Form::close() !!}
@endsection