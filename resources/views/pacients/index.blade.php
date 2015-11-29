@extends('layouts.layout')

@section('content')
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Primer cognom</th>
            <th>Segon cognom</th>
            <th>Accions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pacients as $pacient)
            <tr>
                <td>{{ $pacient->name }}</td>
                <td>{{ $pacient->surname }}</td>
                <td>{{ $pacient->lastname }}</td>
                <td><a href="{{ URL::to('histories/pacient/'.$pacient->id) }}">Veure història</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection