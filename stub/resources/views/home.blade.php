<x-layout.default :head="$head" :content="$content" :chain="$chain">
    <h1>{{ $content['header'] }}</h1>

    {!! $content['body'] !!}
</x-layout.default>
