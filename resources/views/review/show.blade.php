@extends('layouts.layout')

@section('content')
    <div ng-controller="ReviewController" class="main-pacients">
        <h2>{{ $pacient->name . ' ' . $pacient->surname . ' ' . $pacient->lastname }}</h2>

        <h4>{{trans('messages.valoracions')}}</h4>
        <div class="review-text">
            <div ng-repeat="date in review | orderBy:'id'">
                <div class="input-prepend width-12">
                    <span  ng-if="!isToday(date.id)">[[date.date]]</span>
                    <span title="{{trans('messages.edita_review')}}" ng-if="isToday(date.id)" ng-click="editDateReview(date)">[[date.date]]</span>

                    <article ng-click="showReview(date)" ng-if="edit != [[date.id]]"><pre>[[date.text]]</pre></article>
                    <textarea ng-if="edit == [[date.id]]" ng-class="{'show-review' : animate}"
                              ng-model="date.text" ng-keypress="checkKey($event)"></textarea>
                </div>
            </div>
            <div ng-if="review.length == 0">
                {{ trans('messages.no_items') }}
            </div>
        </div>

        {!! Form::model($review, array('class' => 'forms', 'name' => 'form', 'ng-submit' => 'submitForm($event)')) !!}

        {!! Form::hidden('review', null, ['id' => 'review']) !!}
        {!! Form::hidden('patient_id') !!}
        {!! Form::hidden('id') !!}
        <section>
            {!! Form::button('<i class="fa fa-clock-o"></i>',['type' => 'primary', 'title' => '[[actualDate]]', 'class' => 'add_date', 'ng-mouseover' => 'showActualHour()', 'ng-click' => 'addDateToReview($event)']) !!}
        </section>
        <div print-section ng-repeat="date in dates">
            <div class="input-prepend width-12">
                <span>[[date.date]]</span>
                <textarea ng-model="date.text"></textarea>
            </div>
        </div>
        <section>
            @if ($review->id == '')
                {!! Form::button(trans('messages.create', ['name' => 'valoració']),['type' => 'primary', 'ng-show' => 'dates.length || review.length']) !!}
            @else
                {!! Form::button(trans('messages.save', ['name' => 'valoració']),['type' => 'primary', 'ng-show' => 'dates.length || review.length']) !!}
            @endif
        </section>
        {!! Form::close() !!}
        <button ng-click="print()" type="primary"><i class="fa fa-print"></i></button>
    </div>

@endsection