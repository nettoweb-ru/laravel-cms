@props(['id' => 'tabs', 'current' => 1, 'tabs', 'class' => [], 'conditions'  => []])

@pushonce('head')
    @vite([
        'resources/css/netto/tabs.css',
        'resources/js/netto/tabs.js',
    ])
@endpushonce

<div class="tab-hold js-tabs" data-current="{{ $current }}" data-id="{{ $id }}">
    <div class="tab-block navigation">
        <div class="table tab-nav">
            @foreach ($tabs as $key => $value)
                @if (!array_key_exists($key, $conditions) || $conditions[$key])
                    <div class="cell tab-cell title js-switch-tab @if (!empty($class[$key])) {{ $class[$key] }} @endif " data-id="{{ $key }}">
                        <span class="text">{{ __($value) }}</span>
                    </div>
                @endif
                @if ($loop->last)
                    <div class="cell tab-cell last"></div>
                @else
                    <div class="cell tab-cell space">
                        <span class="text"></span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="tab-block tabs">
        @foreach ($tabs as $key => $value)
            @if (array_key_exists($key, $conditions) && !$conditions[$key])
                @continue
            @endif
            <div class="tab js-tab @if (!empty($class[$key])) {{ $class[$key] }} @endif " data-id="{{ $key }}">
                <div class="table tab-padding">
                    <div class="cell tab-padding">
                        {!! ${"tab{$key}"} !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
