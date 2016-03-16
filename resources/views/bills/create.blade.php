@extends('layouts.layout12')

@section('content')
    <column cols="12" offset="1" ng-controller="BillController" class="bill-content">
        <h2>{{trans('models.Billtitle')}}</h2>
        <span style="display: none">[[urlBillInfo = '{{ URL::route('urlBillInfo') }}';""]]</span>
        @if ($bill->exists)
            <span id="bill" style="display:none;">{{$bill->toJson()}}</span>
            {!! Form::model($bill, array('route' => array('updateBill', $bill->id), 'class' => 'forms login-form', 'name' => 'form', 'novalidate' => '')) !!}
        @else
            {!! Form::model($bill, array('route' => array('saveBills'), 'class' => 'forms login-form', 'name' => 'form', 'novalidate' => '')) !!}
        @endif
        <row>
            <column cols="6">
                <span style="display:none" ng-init="billInfo = {{$billInfo}}"></span>

                <div class="bill_id-date">
                    <section>
                        <label>{{trans('models.Billid')}}</label>: <input ng-click="autocomplete_id()" type="text"
                                                                          class="width-2" name="id"
                                                                          ng-model="bill.id">
                        <input type="hidden" name="last_id" ng-init="lastId = {{$last_id}}" value="[[lastId]]">
                    </section>
                    <section>
                        <label>{{trans('models.Billcreationdate')}}</label>: <input placeholder="dd/mm/yyyy" type="text"
                                                                                    class="width-5" name="creation_date"
                                                                                    ng-model="bill.creation_date">
                    </section>
                </div>
                <div class="client-info">
                    <input type="hidden" name="client_id_form" ng-model="client.id">
                    <input type="hidden" name="client_id" value="[[bill.client_id]]">
                    <input type="hidden" name="patient_id_form" ng-model="patient.id">
                    <input type="hidden" name="patient_id" value="[[bill.patient_id]]">
                    <input type="hidden" name="url_search_patients_clients"
                           value="[[searchUrl = '{{URL::route('urlSearch', ['term' => ''])}}']]">
                    <section>
                        <label>{{trans('models.Clientname')}}</label>: <input type="text" class="width-2"
                                                                              ng-keyup="search_clients_and_patients()"
                                                                              name="client_name"
                                                                              autocomplete="off"
                                                                              ng-model="client.name">

                        <div ng-show="autocomplete" class="autocomplete-list" ng-style="{width: widthSearchInput}">
                            <ul>
                                <li ng-repeat="p in pacients" ng-click="put_on_bill(p, 'pacient')">
                                    <span ng-bind-html='underline_word(p.full_name)'></span> ([[p.nif]])
                                </li>
                            </ul>
                            <ul>
                                <li ng-repeat="c in clients" ng-click="put_on_bill(c, 'client')">
                                    <span ng-bind-html='underline_word(c.name)'></span> ([[c.cif]])
                                </li>
                            </ul>
            <span style="display:none"
                  ng-init="pacientUrl = '{{ URL::route('valoracions.pacient.show', ['id' => '']) }}'"></span>
                        </div>
                    </section>
                    <section>
                        <label>{{trans('models.Clientaddress')}}</label>: <input type="text" class="width-2"
                                                                                 name="client_address"
                                                                                 ng-model="client.address">
                    </section>
                    <section>
                        <label>{{trans('models.Clientcity')}}</label>: <input type="text" class="width-2"
                                                                              name="client_city"
                                                                              ng-model="client.city">
                    </section>
                    <section>
                        <label>{{trans('models.Clientcif')}}</label>: <input type="text" class="width-2"
                                                                             name="client_cif"
                                                                             ng-model="client.cif">
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
            <column cols="12">
                <table>
                    <thead>
                    <tr>
                        <th>{{trans('models.Billcode')}}</th>
                        <th>{{trans('models.Billconcept')}}</th>
                        <th>{{trans('models.Billunits')}}</th>
                        <th>{{trans('models.Billunitprice')}}</th>
                        <th>{{trans('models.Billsubtotal')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{--<input type="text" name="concept_code" ng-model="bill.concept_code">--}}</td>
                        <td><input type="text" name="concept" ng-model="bill.concept"></td>
                        <td><input type="text" class="small-input" name="qty"
                                   ng-model="bill.qty">
                        </td>
                        <td><input type="text" class="small-input" name="price_per_unit"
                                   ng-model="bill.price_per_unit"></td>
                        <td>[[bill.total = bill.price_per_unit.replace(',', '.') * bill.qty|currency]]</td>
                    </tr>
                    <tr ng-repeat="t in count(5) track by $index">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <div class="bill-payment-info">
                    <section>
                        <label><strong>{{trans('models.Billpaymentmethod')}}</strong></label>
                        <select name="payment_method" ng-model="bill.payment_method">
                            <option value="cash">Efectiu</option>
                            <option value="bank_transfer">Transferència al compte IBAN</option>
                        </select>
                        <span class="bank-account-number" ng-if="bill.payment_method == 'bank_transfer'">[[Base64.decode(billInfo.account)]]</span>
                    </section>
                </div>
                <div class="bill-amount-info">
                    <span class="subtotal-info"><label><strong>{{trans('models.Billsubtotal')}}</strong></label> <span
                                class="subtotal">[[bill.total|currency]]</span></span>

                    <div class="bill-discount">
                        <label><strong>{{trans('models.Billdiscount')}}</strong></label>
                        <span class="percent">
                            <input type="text" name="discount" ng-model="bill.discount"> %
                        </span>
                        <span class="discount-amount">[[bill.amount_discount = bill.total * bill.discount / 100|currency]]</span>
                    </div>
                    <div class="bill-iva">
                        <label ng-if="bill.iva == '' || !bill.iva"><strong>{{trans('models.Billwoiva')}}</strong></label>
                        <span class="iva-amount">[[bill.total_partial = bill.total - bill.amount_discount|currency]]</span>
                    </div>
                </div>
                <div class="bill-expiration-date">
                    <label><strong>{{trans('models.Billexpirationdate')}}</strong></label>
                    <input placeholder="dd/mm/yyyy" type="text" class="width-5" name="expiration_date"
                           ng-model="bill.expiration_date">
                </div>
                <div class="bill-irpf">
                    <label><strong>{{trans('models.Billirpf')}} [[bill.total_partial|currency]]</strong></label>
                    <span class="percent">
                            <input type="text" name="irpf" ng-model="bill.irpf"> %
                        </span>
                    <span class="discount-amount">-[[bill.amount_irpf = bill.total_partial * bill.irpf / 100|currency]]</span>
                    <input type="hidden" name="amount" value="[[bill.total_bill]]">
                </div>
                <div class="bill-total">
                    <label><strong>{{trans('models.Billnoiva')}}</strong></label>
                    <span class="total">[[show_total(bill.total_partial, bill.amount_irpf)]]</span>
                </div>
            </column>
        </row>
        <section>
            {!! Form::button(trans('messages.create_bill', ['name' => 'pacient']),['type' => 'primary', 'ng-if'=>!$bill->exists, 'ng-disabled' => 'form.$invalid']) !!}
            {!! Form::button(trans('messages.update_bill', ['name' => 'pacient']),['type' => 'primary', 'ng-if'=>$bill->exists, 'ng-disabled' => 'form.$invalid']) !!}
        </section>
        {!! Form::close() !!}
    </column>
@endsection