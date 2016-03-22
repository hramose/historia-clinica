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
                        {!! Form::text('date', null, ['ng-click' => 'today_date()', 'class'=> 'width-12', 'ng-model' => 'review.date']) !!}
                    </section>
                    <section>
                        <label>{{trans('models.Reviewantecedents') }}:</label>
                        <textarea name="review[antecedents]" ng-model="review.review.antecedents"></textarea>
                    </section>
                    <section>
                        <label>{{trans('models.Reviewmotiu_consulta') }}:</label>
                        <textarea name="review[motiu_consulta]" ng-model="review.review.motiu_consulta"></textarea>
                    </section>
                    <section>
                        <label>{{trans('models.Reviewlimitarticular') }}:</label>

                        <div class="container-img-click-lart">
                            <div class="seleccion-nivel">
                                Lleu <img ng-src="{{asset('img/dot-low.png')}}" ng-click="set_selected_dot('low')"
                                          class="low-dot"/>
                                Moderat <span ng-click="set_selected_dot('medium')" class="medium-dot"></span>
                                Greu <img ng-src="{{asset('img/dot-high.png')}}" ng-click="set_selected_dot('high')"
                                          class="high-dot"/>
                            </div>
                            <img id="human_body_img" usemap="#human_body" src="{{asset('img/human-body.png')}}" alt="">
                            <map id="human_body_image_map" name="human_body">
                                <area body_part="ed" full="Espatlla dreta" shape="circle" alt="" title="" coords="85,113,16" href="#"/>
                                <area body_part="ee" full="Espatlla esquerra"shape="circle" alt="" title="" coords="223,113,16" href="#"/>
                                <area body_part="p" full="Pit" shape="circle" alt="" title="" coords="156,165,15" href="#"/>
                                <area body_part="bd" shape="circle" alt="" title="" coords="67,216,16" href="#"/>
                                <area body_part="be" shape="circle" alt="" title="" coords="240,217,16" href="#"/>
                                <area body_part="pi" shape="circle" alt="" title="" coords="154,255,15" href="#"/>
                                <area body_part="zp" shape="circle" alt="" title="" coords="156,338,16" href="#"/>
                                <area body_part="md" shape="circle" alt="" title="" coords="46,338,16" href="#"/>
                                <area body_part="me" shape="circle" alt="" title="" coords="262,340,16" href="#"/>
                                <area body_part="mud" shape="circle" alt="" title="" coords="95,372,16" href="#"/>
                                <area body_part="mue" shape="circle" alt="" title="" coords="213,375,16" href="#"/>
                                <area body_part="rd" shape="circle" alt="" title="" coords="102,481,16" href="#"/>
                                <area body_part="re" shape="circle" alt="" title="" coords="210,482,16" href="#"/>
                                <area body_part="pd" shape="circle" alt="" title="" coords="119,603,16" href="#"/>
                                <area body_part="pe" shape="circle" alt="" title="" coords="190,603,16" href="#"/>
                            </map>
                        </div>
                        <span ng-show="show_msg">{{trans('messages.dot_not_selected')}}</span>
                        <input type="hidden" name="review[limit_articular][dots]" value="[[review.review.limit_articular.dots]]">
                    </section>
                    <section class="send-button">
                        {!! Form::button(trans('messages.save_review'),['type' => 'primary', 'ng-disabled' => 'formReview.$invalid']) !!}
                    </section>
                </column>
                <column cols="6" class="separation">
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