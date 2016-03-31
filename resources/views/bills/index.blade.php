@extends('layouts.layout')

@section('content')
    <table ng-controller="BillController">
        <thead>
        <tr>
            <th>ID</th>
            <th>Data creació</th>
            <th>Data venciment</th>
            <th>Tipus pagament</th>
            <th>Total</th>
            <th>Accions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bills as $bill)
            <tr>
                <td>{{ $bill->id }}</td>
                <td>[[format_date('{{ $bill->creation_date }}')]]</td>
                <td>[[format_date('{{ $bill->expiration_date }}')]]</td>
                <td>{{trans('models.Bill'.$bill->payment_method)}}</td>
                <td>{{$formatter->formatCurrency($bill->amount, 'EUR')}}</td>
                <td>
                    <a title="Editar factura" href="{{ URL::route('mostrarBill', $bill->id) }}"><i
                                class="fa fa-file-text"></i></a>
                    <a title="Veure PDF" href="{{ URL::route('billPdf', $bill->id) }}"><i
                                class="fa fa-file-pdf-o"></i></a>
                    <a title="Eliminar factura" ng-click="ask_question($event)" href="{{ URL::route('eliminarBill', $bill->id) }}"><i
                                class="fa fa-remove"></i></a>
                </td>
            </tr>
        @endforeach
        @if (count($bills) == 0)
            <tr>
                <td style="text-align: center" colspan="6">No s'han trobat factures</td>
            </tr>
        @endif
        </tbody>
    </table>

    {!! $bills->render() !!}
    <a class="buttons" href="{{ URL::route('ferBills') }}"><i class="fa fa-plus"></i></a>
@endsection