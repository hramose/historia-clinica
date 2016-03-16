@extends('layouts.layout')

@section('content')
    <table ng-controller="BillController">
        <thead>
        <tr>
            <th>ID</th>
            <th>Data creació</th>
            <th>Data venciment</th>
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
                <td>[['{{$bill->amount}}'|currency]]</td>
                <td>
                    <a title="Editar factura" href="{{ URL::route('mostrarBill', $bill->id) }}"><i
                                class="fa fa-file-text"></i></a>
                </td>
            </tr>
        @endforeach
        @if (count($bills) == 0)
            <tr>
                <td style="text-align: center" colspan="4">No s'han trobat factures</td>
            </tr>
        @endif
        </tbody>
    </table>

    {!! $bills->render() !!}
@endsection