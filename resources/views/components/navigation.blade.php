@switch ($mode)
    @case(1)
        @foreach ($items as $item)
            <div class="menu-item @if ($item['current']) active @endif">
                <div class="menu-item-block title">
                    <span class="text">{{ $item['name'] }}</span>
                </div>
                @if (!empty($item['items']))
                    <div class="menu-item-block dropdown">
                        @foreach ($item['items'] as $kid)
                            <div class="menu-item-kid @if ($kid['current']) active @else js-link @endif" data-url="{{ $kid['url'] }}">
                                <span class="text">{{ $kid['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
        @break
    @case(2)
        @foreach ($items as $key => $item)
            <div class="menu-item @if ($item['current']) active @endif js-desktop-menu-show" data-id="{{ $key }}">
                <span class="text">{{ $item['name'] }}</span>
            </div>

        @endforeach
        @break
    @case(3)
        @foreach ($items as $key => $item)
            @if (!empty($item['items']))
                <div class="layer layer-dropdown dropdown-{{ config('text_dir') == 'ltr' ? 'normal' : 'reversed' }} desktop menu js-desktop-menu" data-id="{{ $key }}">
                    @foreach ($item['items'] as $kid)
                        <div class="menu-item-kid @if ($kid['current']) active @else js-link @endif" data-url="{{ $kid['url'] }}">
                            <span class="text">{{ $kid['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
        @break
@endswitch
