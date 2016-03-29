<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura #{{$bill->id}}</title>
    <link rel="stylesheet" href="{{ URL::asset('/css/pdf.css') }}">
</head>
<body ng-controller="AppController">
<page size="A4">
    <div id="main-bill" ng-controller="PdfController">
        <div id="titulo-factura">
            <h2>FACTURA</h2>
        </div>
        <div id="datos-personales">
            <div id="datos-factura">
                <p><strong>Número:</strong> <span>{{$bill->id}}</span></p>

                <p><strong>Data:</strong>
                    <span>{{ \Carbon\Carbon::createFromFormat('Y-m-d', explode(' ', $bill->creation_date)[0])->format('d/m/Y') }}</span>
                </p>
            </div>
            <div id="datos-persona">
                <p><strong>{{$billInfo['name']}}</strong></p>

                <p>{{$billInfo['address']}}</p>

                <p>{{$billInfo['city']}}</p>

                <p><strong>{{$billInfo['dni']}}</strong></p>
            </div>
            <div id="imagotipo">
                <p><img src="{{asset('img/logo-responsive.png')}}" alt=""></p>
            </div>
        </div>
        <div id="datos-cliente">
            @if ($bill->patient_id != null)
                <p>
                    <strong>Client:</strong>
                    <span><strong>{{$bill->patient->name}} {{$bill->patient->surname}} {{$bill->patient->lastname}}</strong></span>
                </p><p>
                    <strong>Domicili:</strong> <span>{{$bill->patient->address}}</span>
                </p><p>
                    <strong>Ciutat:</strong> <span>{{$bill->patient->city}}</span>
                </p>
                <p>
                    <strong>CIF:</strong> <span>{{$bill->patient->nif}}</span>
                </p>
            @endif
            @if ($bill->client_id != null)
                <p>
                    <strong>Client:</strong>
                    <span><strong>{{$bill->client->name}}</strong></span>
                </p><p>
                    <strong>Domicili:</strong> <span>{{$bill->client->address}}</span>
                </p><p>
                    <strong>Ciutat:</strong> <span>{{$bill->client->city}}</span>
                </p>
                <p>
                    <strong>CIF:</strong> <span>{{$bill->client->cif}}</span>
                </p>
            @endif
        </div>
        <table cellspacing="0">
            <thead>
            <tr>
                <th>Codi</th>
                <th>Servei/Concepte</th>
                <th>Unitats</th>
                <th>Preu unit.</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td>{{$bill->concept}}</td>
                <td>{{$bill->qty}}</td>
                <td>{{number_format($bill->price_per_unit, 2, ',', '.')}} &euro;</td>
                <td>{{number_format(($bill->qty * $bill->price_per_unit), 2, ',', '.')}} &euro;</td>
            </tr>
            @for($i = 0; $i < 10; $i++)
                <tr class="no-ver">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
            </tbody>
        </table>
        <div id="datos-factura-total">
            <div id="datos-pago">
                <h3>Forma de pagament</h3>
                <span>{{trans('models.Bill'.$bill->payment_method)}}</span>
                @if($bill->payment_method == "bank_transfer")
                    <span>{{base64_decode($billInfo['account'])}}</span>
                @endif
            </div>
            <div id="datos-subtotal">
                <strong>Subtotal</strong>
                <span>{{number_format(($bill->qty * $bill->price_per_unit), 2, ',', '.')}} &euro;</span>
            </div>
            <div id="datos-descuento">
                <strong>Descompte</strong>
                <span>{{number_format(($bill->discount), 2, ',', '.')}} %</span>
                <span>{{number_format((($bill->qty * $bill->price_per_unit) * $bill->discount) / 100, 2, ',', '.')}}
                    &euro;</span>
                <strong class="no_iva">{{trans('models.Billwoiva')}}</strong>
                <span>{{number_format(($bill->qty * $bill->price_per_unit) - (($bill->qty * $bill->price_per_unit) * $bill->discount) / 100, 2, ',', '.')}} &euro;</span>
            </div>
            <div id="datos-vencimiento">
                <h3>Venciment</h3>
                <span>{{ \Carbon\Carbon::createFromFormat('Y-m-d', explode(' ', $bill->expiration_date)[0])->format('d/m/Y') }}</span>
            </div>
            <div id="datos-irpf">
                <p>
                    <span>
                        <strong>
                            {{trans('models.Billirpf')}} {{number_format(($bill->qty * $bill->price_per_unit), 2, ',', '.')}} &euro;
                        </strong>
                    </span>
                    <span>
                       <strong>
                           {{number_format($bill->irpf, 2, ',', '.')}}%
                       </strong>
                    </span>
                    <span>
                        -{{number_format((($bill->qty * $bill->price_per_unit) * $bill->irpf) / 100, 2, ',', '.')}} &euro;
                    </span>
                </p>
                <span id="bill-no-iva">{{trans('models.Billnoiva')}}</span>
            </div>
            <div id="datos-colegiado">
                <span>{!! trans('models.Billcolegiado') !!}</span>
            </div>
            <div id="datos-total">
                <strong>{{trans('models.Billtotalbill')}}</strong>
                <span>
                    {{number_format($bill->qty * $bill->price_per_unit - (($bill->qty * $bill->price_per_unit) * $bill->irpf) / 100, 2, ',', '.')}} &euro;
                </span>
            </div>
        </div>
        <div id="datos-colegiado">
            <span class="fdo">
                {{ trans('messages.bill_colegiado_fdo') }}
            </span>
             <span class="num">
                {{ trans('messages.bill_colegiado_num') }}
            </span>
        </div>
    </div>
</page>
</body>
</html>