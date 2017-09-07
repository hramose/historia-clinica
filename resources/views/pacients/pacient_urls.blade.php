<div>
    <h5>{{trans('messages.pacient_links')}}</h5>
    @foreach ($links as $link)
        <a href="{{$link['url']}}"><span class="fa {{$link['icon']}}"></span></a>
    @endforeach
</div>