@extends('layouts.layout')

@section('content')
    <form action="{{ URL::to('auth/login') }}">
        <input type="hidden" value="{{ csrf_token() }}" name="_token">

        <div>
            <input type="email" name="email">
        </div>
        <div>
            <input type="password" name="password">
        </div>

        <div>
            <input type="submit" value="Login">
        </div>

    </form>

    <a href="{{ URL::to('auth/register') }}">{{ trans('messages.register') }}</a>
@endsection