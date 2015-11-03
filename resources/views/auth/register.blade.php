@extends('layouts.layout')

@section('content')
    {!! Form::open(['route' => 'auth/register', 'class' => 'forms register-form']) !!}

    <section>
        <label>{{ trans('models.Username') }}</label>
        {!! Form::input('text', 'name', '', ['class'=> '']) !!}
    </section>
    <section>
        <label>{{ trans('models.Useremail') }}</label>
        {!! Form::email('email', '', ['class'=> '']) !!}
    </section>
    <section>
        <label>{{ trans('models.Userpassword') }}</label>
        {!! Form::password('password', ['class'=> '']) !!}
    </section>

    <section>
        <label>{{ trans('models.Userconfirmationpassword') }}</label>
        {!! Form::password('password_confirmation', ['class'=> '']) !!}
    </section>

    {{--<section>
        <label>{{ trans('models.Userrol') }}</label>
        {!! Form::select('rol_id', $rols, ['class'=> '']) !!}
    </section>--}}

    <section>
        {!! Form::button(trans('messages.register'),['type' => 'primary']) !!}
    </section>
    {!! Form::close() !!}
    <div id="link-container">
        <a href="{{ URL::to('auth/login') }}">{{ trans('messages.login') }}</a>
    </div>
@endsection