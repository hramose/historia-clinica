<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.min.js"></script>
{!! Html::script('bower_components/angularPrint/angularPrint.js') !!}
<script src="{{asset(elixir('js/desktop.js'))}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-range/3.0.3/moment-range.min.js"></script>
<script src="https://unpkg.com/custom-input/dist/custom-input.js"></script>
<script src="https://unpkg.com/angular-datetime-input/dist/datetime.js"></script>
<script>
    window['moment-range'].extendMoment(moment);
</script>