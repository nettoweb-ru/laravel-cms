@pushonce('head')
    @vite([
        'resources/css/netto/list.widget.css',
        'resources/js/netto/list.widget.js',
    ])
@endpushonce

@php \Netto\Services\CDNService::load('jquery.longpress') @endphp

@props([
    'id',
    'class',
    'head' => '',
    'buttons',
    'showNav' => false,
    'url',
    'noSort' => [],
    'defaultSort' => [],
])

<div class="ajax-list {{ $class }}" id="{{ $id }}" data-url="{{ $url }}" data-no-sort="{{ json_encode($noSort) }}" data-show-navigation="{{ (int) $showNav }}" data-default-sort="{{ json_encode($defaultSort) }}">
    <div class="ajax-list-block top">
        <div class="table block-top-table">
            <div class="cell block-top-cell head">
                {{ $head }}
            </div>
            <div class="cell block-top-cell buttons">
                {{ $buttons }}
            </div>
        </div>
    </div>
    <div class="ajax-list-block content">
        <div class="ajax-content-layer animation js-layer-animation">
            <div class="table">
                <div class="cell">
                    <div class="loading"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
        </div>
        <div class="ajax-content-layer results js-layer-results">
            <div class="ajax-results found js-layer-results-found">
                <div class="ajax-result data">
                    {{ $slot }}
                </div>
                <div class="ajax-result bottom">
                    <div class="table result-bottom-table">
                        @if ($showNav)
                            <div class="cell result-bottom-cell per-page">
                                <label>
                                    <select class="select text js-per-page disabled" disabled name="per-page" title="{{ __('main.title_per_page') }}">
                                        @foreach ([10, 20, 50] as $item)
                                            <option value="{{ $item }}">{{ format_number($item) }}</option>
                                        @endforeach
                                        <option value="0">{{ __('main.general_list_all') }}</option>
                                    </select>
                                </label>
                            </div>
                        @endif
                        <div class="cell result-bottom-cell total">
                            <div>
                                <span class="text">{{ __('main.general_list_total') }}:</span>
                            </div>
                            <div>
                                <span class="text js-total">0</span>
                            </div>
                        </div>
                        @if ($showNav)
                            <div class="cell result-bottom-cell navigation">
                                <div class="table result-bottom-nav-table">
                                    <div class="cell result-bottom-nav-cell padding">
                                        <span class="text">{{ __('main.general_list_page') }}</span>
                                    </div>
                                    <div class="cell result-bottom-nav-cell">
                                        <label>
                                            <select class="select text disabled js-page" disabled name="page"></select>
                                        </label>
                                    </div>
                                    <div class="cell result-bottom-nav-cell padding">
                                        <span class="text">/</span>
                                    </div>
                                    <div class="cell result-bottom-nav-cell">
                                        <span class="text js-pages">{{ format_number(0) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="ajax-results empty js-layer-results-empty">
                <p class="text">
                    {{ __('main.general_list_empty') }}
                </p>
            </div>
        </div>
    </div>
</div>
