@extends('layouts.layout')

@section('content')
    <div id="birthday-list">
        <form action="{{URL::route('notifiedPacientsBirthday')}}" method="post">
            {{ csrf_field() }}
            <table id="birthdays">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Data</th>
                    <th>Edat</th>
                    @if (!isset($no_check))
                        <th>Avisat</th> @endif
                </tr>
                </thead>
                <tbody>
                @foreach($birthdays as $b_pacients)
                    <tr>
                        <td>{{ $b_pacients->full_name }}</td>
                        <td>
                            {{ $b_pacients->birth_date->formatLocalized('%e %B') }}
                        </td>
                        <td>
                            {{ (new \Carbon\Carbon())->diffInYears($b_pacients->birth_date) }}
                            farà
                            {{ (new \Carbon\Carbon())->diffInYears($b_pacients->birth_date)+1 }}
                        </td>
                        @if (!isset($no_check))
                            <td>
                                <input type="checkbox" id="cb{{$b_pacients->id}}" value="{{$b_pacients->id}}"
                                       name="patient_id[]">
                                <label for="cb{{$b_pacients->id}}"></label>
                            </td>
                        @endif
                    </tr>
                @endforeach
                @if (count($birthdays) == 0)
                    <tr>
                        <td style="text-align: center" colspan="4">No s'han trobat aniversaris</td>
                    </tr>
                @endif
                </tbody>
            </table>
            @if (!isset($no_check))
                <button type="primary">{{trans('messages.send')}}</button>
            @endif
        </form>
    </div>
@endsection