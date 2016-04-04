<base href="{{ URL::to('/') }}">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
@if (session('isMobile'))
    <link rel="stylesheet" href="{{ asset(elixir('css/mobile.css')) }}">
@else
    <link rel="stylesheet" href="{{ asset(elixir('css/desktop.css')) }}">
@endif
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script>
    var base_url = "{{ URL::to('/') }}";
</script>