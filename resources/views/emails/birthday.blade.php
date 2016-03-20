<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<p>{{trans('messages.pacients_birthday')}}</p>
<ul>
    @foreach($pacients as $pacient)
        <li>
            {{--*/ @$date =  new \Carbon\Carbon($pacient->birth_date) /*--}}
            {{
            trans('messages.pacient_line_birthday', [
                'full_name' => $pacient->full_name,
                'email' => $pacient->email,
                'phone' => $pacient->phone,
                'age' => $pacient->age + 1,
                'date' => $date->format('d F')
            ])
            }}
        </li>
    @endforeach
</ul>
</body>
</html>