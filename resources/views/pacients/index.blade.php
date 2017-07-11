@extends('layouts.layout')

@section('content')
    <div ng-controller="PacientsController">
        <form id="search_pacient_form" action="[[location]]" name="formSearch">
            <span ng-init="location = '{{URL::route('pacientsListFront', '')}}/'" style="display:none"></span>
            {{csrf_field()}}
            <label>{{ trans('models.Pacientcerca') }}</label>
            <div class="btn-append">
                {!! Form::input('text', 'term', '', ['class'=> 'width-12', 'name' => 'term', 'ng-model' => 'search.term', 'ng-keyup' => 'search_pacient(formSearch)', 'placeholder' => 'Escriu el nom o el ID del pacient...']) !!}
            </div>
            <div ng-show="patients.length > 0" class="autocomplete">
                <ul>
                    <li ng-repeat="p in patients">
                        <div>
                            <span ng-bind-html='underline_word(p.full_name)'></span>
                                <span class="actions">
                                    <a href="{{URL::route('pacientsDades', '')}}/[[p.id]]"><i
                                                class="fa fa-user"></i></a>
                                    <a href="{{URL::route('curso.pacient.show', '')}}/[[p.id]]"><i
                                                class="fa fa-list"></i></a>
                                    <a href="{{URL::route('valoracions.pacient.show', '')}}/[[p.id]]"><i
                                                class="fa fa-calendar-check-o"></i></a>
                                </span>
                        </div>
                    </li>
                </ul>
            </div>
        </form>
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
                    <td>
                        <a title="Dades del pacient" href="{{ URL::to('pacients/dades/'.$pacient->id) }}"><i
                                    class="fa fa-user"></i></a>
                        <a title="Veure història" href="{{ route('curso.pacient.show', ['patient' => $pacient]) }}"><i
                                    class="fa fa-list"></i></a> <a title="Realitzar valoració"
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
    </div>
@endsection