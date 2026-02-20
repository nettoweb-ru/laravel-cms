@php \Netto\Services\AssetService::load('jquery.longpress') @endphp

@pushonce('head')
    @vite([
        'resources/css/netto/list.scss',
        'resources/js/netto/list.widget.js',
    ])
@endpushonce

@props([
    'id',
    'class',
    'head' => '',
    'buttons' => '',
    'search' => '',
    'showNav' => false,
    'showTotal' => true,
    'url',
    'noSort' => [],
    'defaultSort' => [],
    'ltr' => [],
])

<div class="list {{ $class }}" id="{{ $id }}" data-url="{{ $url }}" data-no-sort="{{ json_encode($noSort) }}" data-default-sort="{{ json_encode($defaultSort) }}" data-ltr="{{ json_encode($ltr) }}">
    @if ($head || $buttons)
        <div class="list-block top">
            <div class="table block-top-table">
                <div class="cell block-top-cell head">
                    {{ $head }}
                </div>
                <div class="cell block-top-cell buttons">
                    {{ $buttons }}
                </div>
            </div>
        </div>
    @endif
    <div class="list-block content">
        <div class="content-layer animation js-layer-animation">
            <div class="table">
                <div class="cell">
                    <div class="loading"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
        </div>
        <div class="content-layer results js-layer-results">
            {{ $search }}
            <div class="data-block found js-layer-results-found">
                <div class="result data">
                    {{ $slot }}
                </div>
                <div class="result bottom">
                    <div class="table result-bottom-table">
                        @if ($showNav)
                            <div class="cell result-bottom-cell per-page">
                                <label>
                                    <select class="input text js-per-page disabled" disabled name="per-page" title="{{ __('main.title_per_page') }}">
                                        @foreach ([10, 20, 50] as $item)
                                            <option value="{{ $item }}">{{ format_number($item) }}</option>
                                        @endforeach
                                        <option value="0">{{ __('main.general_list_all') }}</option>
                                    </select>
                                </label>
                            </div>
                        @endif
                        @if ($showTotal)
                            <div class="cell result-bottom-cell total">
                                <div>
                                    <span class="text">{{ __('main.general_list_total') }}:</span>
                                </div>
                                <div>
                                    <span class="text js-total">0</span>
                                </div>
                            </div>
                        @endif
                        @if ($showNav)
                            <div class="cell result-bottom-cell navigation">
                                <div class="table result-bottom-nav-table">
                                    <div class="cell result-bottom-nav-cell padding">
                                        <span class="text desktop">{{ __('main.general_list_page') }}</span>
                                    </div>
                                    <div class="cell result-bottom-nav-cell">
                                        <label>
                                            <select class="input text disabled js-page" disabled name="page"></select>
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
            <div class="data-block empty js-layer-results-empty">
                <span class="text">
                    {{ __('main.general_list_empty') }}
                </span>
            </div>
        </div>
    </div>
</div>
