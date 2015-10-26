@extends('layouts.layout')

@section('content')
    <form action="login">
        <input type="hidden" value="{{ csrf_token() }}" name="_token">

        <div>
            <input type="email" name="email">
        </div>
        <div>
            <input type="password" name="password">
        </div>

    </form>

    <a href="{{ URL::to('auth/register') }}">Registra't</a>
@endsection