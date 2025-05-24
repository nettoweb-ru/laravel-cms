<div class="menu">
    @foreach ($items as $key => $item)
        <div class="menu-item-kid @if ($item['current']) active @else js-set-language @endif {{ $item['text_dir'] }}" data-code="{{ $key }}">
            <span class="text">{{ $item['title'] }}</span>
        </div>
    @endforeach
</div>
