@extends('layouts.layout12')

@section('content')
    <column cols="12" offset="1" ng-controller="BillController" class="bill-content">
        <h2>{{trans('models.Billtitle')}}</h2>
        <span style="display: none">[[urlBillInfo = '{{ URL::route('urlBillInfo') }}';""]]</span>
        {!! Form::model($bill, array('route' => array('saveBills'), 'class' => 'forms login-form', 'name' => 'form', 'novalidate' => '')) !!}
        <row>
            <column cols="6">
                <div class="bill_id-date">
                    <section>
                        <label>{{trans('models.Billid')}}</label>: <input type="text" class="width-2" name="id"
                                                                          ng-model="bill.id">
                    </section>
                    <section>
                        <label>{{trans('models.Billdate')}}</label>: <input placeholder="dd/mm/yyyy" type="text"
                                                                            class="width-5" name="date"
                                                                            ng-model="bill.date">
                    </section>
                </div>
            </column>
            <column cols="6">
                <div class="bill_info">
                    <p class="strong">[[billInfo.name]]</p>

                    <p>[[billInfo.address]]</p>

                    <p>[[billInfo.city]]</p>

                    <p class="strong">[[billInfo.dni]]</p>
                </div>
            </column>
        </row>
        {!! Form::close() !!}
    </column>
@endsection