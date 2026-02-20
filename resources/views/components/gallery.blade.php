@pushonce('head')
    @vite([
        'resources/js/netto/gallery.js',
    ])
@endpushonce

@props([
    'class' => 'js-gallery',
    'id' => 'gallery',
    'title' => '',
    'url',
    'actions' => [],
])

<x-cms::ajaxlist :id="$id" :url="$url" :class="$class">
    <x-slot:head>
        <span class="text-big">{{ $title }}</span>
    </x-slot:head>

    <x-slot:buttons>
        @if (!empty($actions['create']))
            <button class="btn btn-bg btn-blue create js-link js-list-button" data-url="{{ $actions['create'] }}" data-type="create" title="{{ __('main.title_create') }}"></button>
        @endif
        <button class="btn btn-bg btn-blue invert-selection js-list-button" data-type="invert" title="{{ __('main.title_invert') }}"></button>
        @if (!empty($actions['toggle']))
            <button class="btn btn-bg btn-blue toggle-on js-list-button" data-type="toggle" data-url="{{ $actions['toggle'] }}" title="{{ __('main.title_toggle') }}"></button>
        @endif
        @if (!empty($actions['delete']))
            <button class="btn btn-bg btn-red remove js-list-button" data-type="delete" data-url="{{ $actions['delete'] }}" title="{{ __('main.title_delete') }}"></button>
        @endif
    </x-slot:buttons>

    <div class="gallery js-body"></div>
</x-cms::ajaxlist>
