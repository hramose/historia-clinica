@extends('layouts.layout')

@section('content')
    {{ trans('messages.email_backup_msg', ['date' => $date]) }}
@endsection