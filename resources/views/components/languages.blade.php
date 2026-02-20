@foreach ($items as $key => $item)
    <div class="menu-item-kid @if ($item['current']) active @else js-set-language @endif" data-code="{{ $key }}" dir="{{ $item['text_dir'] }}">
        <span class="text">{{ $item['title'] }}</span>
    </div>
@endforeach
