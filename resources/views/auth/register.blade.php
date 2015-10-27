@extends('layouts.layout')

@section('content')
    {!! Form::open(['route' => 'auth/register', 'class' => 'form']) !!}

    <div class="form-group">
        <label>name</label>
        {!! Form::input('text', 'name', '', ['class'=> 'form-control']) !!}
    </div>
    <div class="form-group">
        <label>Email</label>
        {!! Form::email('email', '', ['class'=> 'form-control']) !!}
    </div>
    <div class="form-group">
        <label>Password</label>
        {!! Form::password('password', ['class'=> 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Password confirmation</label>
        {!! Form::password('password_confirmation', ['class'=> 'form-control']) !!}
    </div>

    <div>
        {!! Form::submit('send',['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}

    <a href="{{ URL::to('auth/login') }}">{{ trans('messages.login') }}</a>
@endsection