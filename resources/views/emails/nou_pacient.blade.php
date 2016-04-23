<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<p>{{trans('messages.new_pacient_request_email')}}</p>
<ul>
    <li><label for="">{{trans('messages.name')}}: {{$name}}</label></li>
    <li><label for="">{{trans('messages.surnames')}}: {{$surnames}}</label></li>
    <li><label for="">{{trans('messages.dni')}}: {{$dni}}</label></li>
    <li><label for="">{{trans('messages.phone')}}: {{$phone}}</label></li>
    <li><label for="">{{trans('messages.obsv')}}: {{$obsv}}</label></li>
</ul>
</body>
</html>