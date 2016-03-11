@extends('layouts.layout12')

@section('content')
    <column cols="5" offset="1">
        <div id="review-content" ng-controller="ReviewController">
            <h2>Dades del pacient</h2>
            {!! Form::model($pacient, array('class' => 'forms', 'name' => 'form')) !!}
            <div id="patient" style="display: none">
                {!! $pacient->toJson() !!}
            </div>
            <row>
                <column cols="3">
                    <section>
                        <label>{{ trans('models.Pacientid') }}</label>
                        {!! Form::text('id', null, ['class'=> 'width-11', 'readonly' => 'readonly', 'ng-model' => 'patient.id']) !!}
                    </section>
                    <section>
                        <label>{{ trans('models.Pacientbirth_date') }}</label>
                        {!! Form::text('birth_date', null, ['class'=> 'width-11', 'readonly' => 'readonly', 'ng-model' => 'patient.birth_date']) !!}
                    </section>
                </column>
                <column cols="6">
                    <section>
                        <label>{{ trans('models.Pacientname') }}</label>
                        {!! Form::text('fullname', null, ['class'=> 'width-12', 'readonly' => 'readonly', 'ng-model' => 'patient.full_name']) !!}
                    </section>
                    <section>
                        <label>{{ trans('models.Pacientage') }}</label>
                        {!! Form::text('age', null, ['class'=> 'width-12', 'readonly' => 'readonly', 'ng-model' => 'patient.age']) !!}
                    </section>
                </column>
            </row>
            {!! Form::close() !!}
            <h2>Valoració de Fisioteràpia</h2>
            {!! Form::model($review, ['route' => ['valoracionsGuarda', $pacient->id], 'class' => 'forms login-form', 'name' => 'formReview', 'novalidate' => '', 'ng-submit' => 'submit_form($event)']) !!}
            <div id="review" style="display: none">
                {!! $review->toJson() !!}
            </div>
            {!! Form::hidden('id', null, ['ng-model' => 'review.id']) !!}
            {!! Form::hidden('patient_id',  null, ['ng-model' => 'review.patient_id']) !!}
            <row>
                <column cols="4">
                    <section>
                        <label>{{ trans('models.Reviewdate') }}</label>
                        {!! Form::text('date', null, ['ng-click' => 'today_date()', 'class'=> 'width-11', 'ng-model' => 'review.date']) !!}
                    </section>
                    <section>
                        <label>{{trans('models.Reviewantecedents') }}:</label>
                        <textarea name="review[antecedents]" ng-model="review.review.antecedents"></textarea>
                    </section>
                    <section>
                        <label>{{trans('models.Reviewmotiu_consulta') }}:</label>
                        <textarea name="review[motiu_consulta]" ng-model="review.review.motiu_consulta"></textarea>
                    </section>
                    <section class="send-button">
                        {!! Form::button(trans('messages.save', ['name' => 'pacient']),['type' => 'primary', 'ng-disabled' => 'formReview.$invalid']) !!}
                    </section>
                </column>
                <column cols="6">
                    <section>
                        <label>{{trans('models.Reviewalldates') }}</label>
                        {!! Form::select('selected_review', [-1 => trans('messages.empty_option_reviews_dates')] + $reviews->lists('date', 'id')->toArray(), $review->id, ['class'=> 'width-7', 'onchange' => 'angular.element(this).scope().edit_review(this)']) !!}
                    </section>
                </column>
            </row>
            {!! Form::close() !!}
        </div>
    </column>
    <column cols="4" id="buscador-content" ng-controller="SearchController">
        {!! Form::open(['route' => ['pacientsSearch', ''], 'class' => 'forms login-form', 'novalidate' => '']) !!}
        <label>{{ trans('models.Pacientcerca') }}</label>
        {!! Form::hidden('url', URL::route('pacientsSearch', ['term' => '']), ['ng-model' => 'search.url', 'name' => 'url', 'id' => 'url']) !!}
        <div class="btn-append">
            {!! Form::input('text', 'term', '', ['class'=> '', 'name' => 'term', 'ng-model' => 'search.term', 'ng-keyup' => 'search_pacient()', 'placeholder' => 'Escriu el nom o el ID del pacient...']) !!}
        </div>
        <div ng-show="autocomplete" class="autocomplete-list" ng-style="{width: widthSearchInput}">
            <ul>
                <li ng-repeat="p in pacients" ng-click="show_review_from(p)">
                    <span ng-bind-html='underline_word(p.full_name)'></span> ([[p.nif]])
                </li>
            </ul>
            <span style="display:none"
                  ng-init="pacientUrl = '{{ URL::route('valoracions.pacient.show', ['id' => '']) }}'"></span>
        </div>
        {!! Form::close() !!}
    </column>

@endsection