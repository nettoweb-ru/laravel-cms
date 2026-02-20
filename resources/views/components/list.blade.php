@php \Netto\Services\AssetService::load('jquery.ui') @endphp

@pushonce('head')
    @vite([
        'resources/js/netto/list.js',
    ])
@endpushonce

@props([
    'class' => 'js-list',
    'id' => 'list',
    'title' => '',
    'url',
    'actions' => [],
    'columns' => ['id' => __('main.attr_id')],
    'default' => ['id'],
    'defaultSort' => ['id' => 'asc'],
    'noSort' => [],
    'search' => true,
    'ltr' => [],
])

<x-cms::ajaxlist :showNav="true" :id="$id" :url="$url" :class="$class" :noSort="$noSort" :defaultSort="$defaultSort" :ltr="$ltr">
    <x-slot:head>
        <span class="text-big">{{ $title }}</span>
    </x-slot:head>

    <x-slot:buttons>
        @if (!empty($actions['create']))
            <button class="btn btn-bg btn-blue create js-link js-list-button" data-url="{{ $actions['create'] }}" data-type="create" title="{{ __('main.title_create') }}"></button>
        @endif
        @if ($search)
            <button class="btn btn-bg btn-blue search js-list-button" data-type="search" title="{{ __('main.title_find') }}"></button>
        @endif
        @if (!empty($actions['downloadCsv']))
            <button class="btn btn-bg btn-blue file-type-csv js-list-button" data-type="downloadCsv" data-url="{{ $actions['downloadCsv'] }}" title="{{ __('main.title_download_csv') }}"></button>
        @endif
        <button class="btn btn-bg btn-blue invert-selection js-list-button" data-type="invert" title="{{ __('main.title_invert') }}"></button>
        @if (!empty($actions['toggle']))
            <button class="btn btn-bg btn-blue toggle-on js-list-button" data-type="toggle" data-url="{{ $actions['toggle'] }}" title="{{ __('main.title_toggle') }}"></button>
        @endif
        @if (!empty($actions['delete']))
            <button class="btn btn-bg btn-red remove js-list-button" data-type="delete" data-url="{{ $actions['delete'] }}" title="{{ __('main.title_delete') }}"></button>
        @endif
    </x-slot:buttons>

    @if ($search)
        <x-slot:search>
            <div class="data-block search js-list-search">
                @foreach ($columns as $key => $value)
                    <div class="table list-column-table">
                        <div class="cell list-column-cell">
                            <x-cms::form.string placeholder="{{ $value }}" name="" class="js-list-search-input" type="text" id="netto-list-{{ $id }}-search-{{ $key }}" data-id="{{ $key }}" />
                        </div>
                    </div>
                @endforeach
            </div>
        </x-slot:search>
    @endif

    <div class="general">
        <div class="visible">
            <table>
                <thead class="js-head">
                <tr></tr>
                </thead>
                <tbody class="js-body"></tbody>
            </table>
        </div>
        <div class="dropdown js-dropdown">
            @foreach ($columns as $key => $value)
                <div class="table list-column-table" data-id="{{ $key }}"
                     data-default="{{ (int) in_array($key, $default) }}">
                    <div class="cell list-column-cell">
                        <span class="text-small">{{ $value }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-cms::ajaxlist>
