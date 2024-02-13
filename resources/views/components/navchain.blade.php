@foreach ($items as $item)
    <span class="chain text-small">
        @if (empty($item['link']))
            {{ $item['title'] }}
        @else
            <a href="{{ $item['link'] }}" class="js-animated-link">{{ $item['title'] }}</a>
        @endif
    </span>
@endforeach
