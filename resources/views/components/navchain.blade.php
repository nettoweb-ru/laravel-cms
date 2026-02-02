@php
    $i = 0;
    $count = count($items);
@endphp
@if ($count)
@foreach ($items as $item)
    @php
        $i++;
    @endphp
    <span class="chain text-small">
        @if ((empty($item['link'])) || ($i == $count))
            {{ $item['title'] }}
        @else
            <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
        @endif
    </span>
@endforeach
@endif
