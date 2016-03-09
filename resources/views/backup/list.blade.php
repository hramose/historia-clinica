@extends('layouts.layout12')

@section('content')
    <h2>{{ trans('messages.lista_backups') }}</h2>
    <ul class="list-backups">
        @foreach($files as $file)
            <li>
                <a href="{{ URL::route('backupDownload', ['file' => base64_encode($file['name'])]) }}"><i
                            class="fa fa-angle-right"></i> {{$file['name']}} ({{$file['date']}})</a>
            </li>
        @endforeach
    </ul>
@endsection