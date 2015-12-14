@extends('layouts.layout')

@section('content')
    <div ng-controller="ReviewController">
        <h2>{{--Valoració del pacient --}}{{ $pacient->name . ' ' . $pacient->surname . ' ' . $pacient->lastname }}</h2>

        <div class="review-text">
            <span style="display:none">{{ nl2br($review->review) }}</span>
        </div>
        {!! Form::model($review, array('class' => 'forms', 'name' => 'form', 'ng-submit' => 'submitForm($event)')) !!}

        {!! Form::hidden('review', null, ['id' => 'review']) !!}
        {!! Form::hidden('patient_id') !!}
        {!! Form::hidden('id') !!}
        <section>
            {!! Form::label('Valoració editable') !!}
            <section>
                {!! Form::button('<i class="fa fa-clock-o"></i>',['type' => 'primary', 'title' => '[[actualDate]]', 'class' => 'add_date', 'ng-mouseover' => 'showActualHour()', 'ng-click' => 'addDateToReview($event)']) !!}
            </section>
            {!! Form::textarea('fake_review', '', ['ng-model' => 'form.review']) !!}
        </section>
        <section>
            @if ($review->id == '')
                {!! Form::button(trans('messages.create', ['name' => 'valoració']),['type' => 'primary']) !!}
            @else
                {!! Form::button(trans('messages.save', ['name' => 'valoració']),['type' => 'primary']) !!}
            @endif
        </section>
        {!! Form::close() !!}
    </div>

@endsection