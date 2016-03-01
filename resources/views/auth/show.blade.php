@extends('layouts.layout')

@section('content')
    <div ng-controller="UsersController">
        {!! Form::model($user, array('route' => array('userDadesUpdate', $user->id), 'class' => 'forms login-form', 'name' => 'form', 'novalidate' => '')) !!}
        <section>
            <label>{{ trans('models.Username') }}</label>
            {!! Form::text('name', null, ['class'=> '', 'ng-model' => 'user.name', 'required' => '', 'autocomplete' => 'off']) !!}
        </section>
        <section>
            <label>{{ trans('models.Useremail') }}</label>
            {!! Form::email('email', null, ['class'=> '', 'ng-model' => 'user.email', 'required' => '', 'autocomplete' => 'off']) !!}
        </section>
        <section>
            <label>{{ trans('models.Userpassword') }}</label>
            {!! Form::password('password', null, ['class'=> '', 'ng-model' => 'user.password', 'required' => '', 'autocomplete' => 'off']) !!}
        </section>
        <section>
            <label>{{ trans('models.Userblocked') }}</label>
            {!! Form::hidden('blocked', 0, ['class'=> '', 'autocomplete' => 'off']) !!}
            {!! Form::checkbox('blocked', null, null, ['class'=> '', 'ng-model' => 'user.blocked', 'autocomplete' => 'off', 'ng-true-value' => 'true']) !!}
        </section>
        <section>
            {!! Form::button(trans('messages.update_user', ['name' => 'user']),['type' => 'primary', 'ng-disabled' => 'form.$invalid']) !!}
        </section>
        {!! Form::close() !!}
        <div class="user_json" style="display: none">
            {!! $user->toJson() !!}
        </div>
    </div>
@endsection