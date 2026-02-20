@php
    $i = 0;
    $count = count($items);
@endphp
<div class="crumbs">
    @foreach ($items as $item)
        @php
            $i++;
        @endphp
        <div class="crumb">
            <span class="text-small">
                @if ((empty($item['link'])) || ($i == $count))
                    {{ $item['title'] }}
                @else
                    <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                @endif
            </span>
        </div>
@endforeach
</div>
