@extends('layouts.layout')

@section('content')
    {!! Form::open(['route' => 'pacients/nou', 'class' => 'forms login-form']) !!}

    {!! Form::close() !!}
@endsection