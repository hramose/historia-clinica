@extends('layouts.layout')

@section('content')
    <table ng-controller="PacientsController">
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
                <td>
                    <a title="Dades del pacient" href="{{ URL::to('pacients/dades/'.$pacient->id) }}"><i
                                class="fa fa-user"></i></a>
                    <a title="Veure història" href="{{ URL::to('histories/pacient/'.$pacient->id) }}"><i
                                class="fa fa-list"></i></a> <a title="Veure i fer valoraciones"
                                                               href="{{ URL::to('valoracions/pacient/'.$pacient->id) }}"><i
                                class="fa fa-calendar-check-o"></i></a><a
                            ng-click="showDeleteModal($event)"
                            href="{{URL::route('pacientsDelete', [$pacient->id])}}"><i class="fa fa-remove"></i></a>
                </td>
            </tr>
        @endforeach
        @if (count($pacients) == 0)
            <tr>
                <td style="text-align: center" colspan="4">No s'han trobat pacients</td>
            </tr>
        @endif
        </tbody>
    </table>

    {!! $pacients->render() !!}
    <a class="buttons" href="{{ URL::route('pacientsNouGet') }}"><i class="fa fa-plus"></i></a>
@endsection