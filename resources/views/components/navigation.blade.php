<div class="menu">
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
    <div class="menu-item">
        <div class="menu-item-block title js-logout">
            <span class="text">{{ __('cms::auth.logout') }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
            </form>
        </div>
    </div>
</div>
