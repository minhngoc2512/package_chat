@if(Session::has('channelChat'))
    @php(
    $array = Session::get('channelChat')
    )

    {{print_r($array)}}
    @else
    {{'no session '}}
    @endif
{{--@php(--}}
{{--Session::forget('channelChat')--}}
{{--)--}}