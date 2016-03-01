@extends('layouts.layout')

@section('content')
    <table ng-controller="UsersController">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Bloquejat</th>
            <th>Accions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->blocked == 1 ? 'Sí' : 'No' }}</td>
                <td>
                    <a title="Dades de l'usuari" href="{{ URL::to('users/dades/'.$user->id) }}"><i
                                class="fa fa-user"></i></a>
                    <a ng-click="showDeleteModal($event)"
                       href="{{URL::route('usersDelete', [$user->id])}}"><i class="fa fa-remove"></i></a>
                </td>
            </tr>
        @endforeach
        @if (count($user) == 0)
            <tr>
                <td style="text-align: center" colspan="4">No s'han trobat usuaris</td>
            </tr>
        @endif
        </tbody>
    </table>

    {!! $users->render() !!}
    <a class="buttons" href="{{ URL::route('userNou') }}"><i class="fa fa-plus"></i></a>
@endsection