<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura #{{$bill->id}}</title>
    <link rel="stylesheet" href="{{ URL::asset('/css/pdf.css') }}">
</head>
<body ng-controller="AppController">
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
        <div class="clearfix">

        </div>
    </div>
    <div id="datos-cliente">
        @if ($bill->patient_id != null)
            <p>
                <strong>Client:</strong> <span>{{$bill->patient->name}} {{$bill->patient->surname}} {{$bill->patient->lastname}}</span>
            </p><p>
                <strong>Domicili:</strong> <span>{{$bill->patient->address}}</span>
            </p><p>
                <strong>Ciutat:</strong> <span>{{$bill->patient->city}}</span>
            </p>
            <p>
                <strong>CIF:</strong> <span>{{$bill->patient->nif}}</span>
            </p>
        @endif
    </div>
</div>
</body>
</html>