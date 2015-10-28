@extends('layouts.layout')

@section('content')
    {{ trans('messages.email_verify') }}
    <a href="{{ URL::to('auth/verify/' . $confirmation_code) }}">{{ trans('messages.verify_now') }}</a>.<br/>
@endsection