@pushonce('head')
    @vite([
        'resources/css/netto/list.css',
        'resources/js/netto/list.js',
    ])
@endpushonce

@php \Netto\Services\CDNService::load('jquery.ui') @endphp

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
])

<x-cms::ajaxlist :showNav="true" :id="$id" :url="$url" :class="$class" :noSort="$noSort" :defaultSort="$defaultSort">
    <x-slot:head>
        <span class="text-big">{{ $title }}</span>
    </x-slot:head>

    <x-slot:buttons>
        @if (!empty($actions['create']))
            <x-cms::form.button type="button" bg="icons.create" class="btn-icon btn-normal disabled js-link js-list-button" data-type="create" data-url="{{ $actions['create'] }}" title="{{ __('main.title_create') }}"/>
        @endif
        @if ($search)
            <x-cms::form.button type="button" bg="icons.search" class="btn-icon btn-normal disabled js-list-button" data-type="search" title="{{ __('main.title_find') }}"/>
        @endif
        <x-cms::form.button type="button" bg="icons.download" class="btn-icon btn-normal hidden disabled js-list-button" data-type="download" title="{{ __('main.title_download_excel') }}"/>
        <x-cms::form.button type="button" bg="icons.invert-selection" class="btn-icon btn-normal disabled js-list-button" data-type="invert" title="{{ __('main.title_invert') }}"/>
        @if (!empty($actions['toggle']))
            <x-cms::form.button type="button" bg="icons.toggle-on" class="btn-icon btn-normal disabled js-list-button" data-type="toggle" data-url="{{ $actions['toggle'] }}" title="{{ __('main.title_toggle') }}"/>
        @endif
        @if (!empty($actions['delete']))
            <x-cms::form.button type="button" bg="icons.remove" class="btn-icon btn-warning disabled js-list-button" data-type="delete" data-url="{{ $actions['delete'] }}" title="{{ __('main.title_delete') }}"/>
        @endif
    </x-slot:buttons>

    @if ($search)
        <x-slot:search>
            <div class="ajax-results search js-list-search">
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

    <div class="list-results">
        <div class="list-results-visible">
            <table>
                <thead class="js-head">
                <tr></tr>
                </thead>
                <tbody class="js-body"></tbody>
            </table>
        </div>
        <div class="list-results-dropdown js-dropdown">
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
