@extends('layouts.layout')

@section('content')
    {!! Form::open(['route' => 'auth/login', 'class' => 'forms login-form']) !!}
    <section>
        <label>{{ trans('models.Useremail') }}</label>
        {!! Form::email('email', '', ['class'=> '']) !!}
    </section>
    <section>
        <label>{{ trans('models.Userpassword') }}</label>
        {!! Form::password('password', ['class'=> '']) !!}
    </section>

    <section>
        <label><input type="checkbox" name="remember"> {{ trans('models.Userrememberme') }}</label>
    </section>

    <section>
        {!! Form::button(trans('messages.login'),['type' => 'primary']) !!}
    </section>
    {!! Form::close() !!}
    <div id="link-container">
        <a href="{{ URL::to('auth/register') }}">{{ trans('messages.register') }}</a>
    </div>
@endsection