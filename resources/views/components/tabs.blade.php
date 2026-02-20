@props(['id' => 'tabs', 'tabs', 'class' => [], 'conditions'  => []])

@pushonce('head')
    @vite([
        'resources/css/netto/tabs.scss',
        'resources/js/netto/tabs.js',
    ])
@endpushonce

@php
    $visible = [];
    foreach ($tabs as $key => $value) {
        if (!array_key_exists($key, $conditions) || $conditions[$key]) {
            $visible[$key] = $value;
        }
    }
@endphp

<div class="tabs js-tabs" data-id="{{ $id }}">
    <div class="tab-block navigation">
        <div class="table">
            @foreach ($visible as $key => $value)
                <div class="cell title js-switch-tab @if (!empty($class[$key])) {{ $class[$key] }} @endif" data-id="{{ $key }}">
                    <span class="text">{{ __($value) }}</span>
                </div>
                <div class="cell @if ($loop->last) last @else space @endif"></div>
            @endforeach
        </div>
    </div>
    <div class="tab-block items">
        @foreach ($visible as $key => $value)
            <div class="tab js-tab @if (!empty($class[$key])) {{ $class[$key] }} @endif " data-id="{{ $key }}">
                <div class="table tab-item-table">
                    <div class="cell tab-item-cell">
                        {!! ${"tab{$key}"} !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
