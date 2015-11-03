@extends('layouts.layout')

@section('content')
    {!! Form::open(['route' => 'crear_menu', 'class' => 'forms register-form']) !!}

    <section>
        <label>Nom menú</label>
        {!! Form::input('text', 'title', '', ['class'=> '']) !!}
    </section>

    <section>
        <label>URL</label>
        {!! Form::input('text', 'url', '', ['class'=> '']) !!}
    </section>

    <section>
        <label>Pare</label>
        {!! Form::select('father_id', $menus, ['class'=> '']) !!}
    </section>

    <section>
        {!! Form::button('Crea',['type' => 'primary']) !!}
    </section>

    {!! Form::close() !!}
@endsection