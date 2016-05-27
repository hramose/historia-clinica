@extends('layouts.layout12')

@section('content')
    {!! Form::model($pacient, array('class' => 'forms', 'name' => 'form')) !!}
        
    {!! Form::close() !!}
@endsection

