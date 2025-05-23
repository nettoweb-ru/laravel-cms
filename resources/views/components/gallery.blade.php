@pushonce('head')
    @vite([
        'resources/css/netto/gallery.css',
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
            <x-cms::form.button type="button" bg="icons.create" class="btn-icon btn-normal disabled js-link js-list-button" data-type="create" data-url="{{ $actions['create'] }}" title="{{ __('main.title_create') }}" />
        @endif
        <x-cms::form.button type="button" bg="icons.invert-selection" class="btn-icon btn-normal disabled js-list-button" data-type="invert" title="{{ __('main.title_invert') }}" />
        @if (!empty($actions['toggle']))
            <x-cms::form.button type="button" bg="icons.toggle-on" class="btn-icon btn-normal disabled js-list-button" data-type="toggle" data-url="{{ $actions['toggle'] }}" title="{{ __('main.title_toggle') }}" />
        @endif
        @if (!empty($actions['delete']))
            <x-cms::form.button type="button" bg="icons.remove" class="btn-icon btn-warning disabled js-list-button" data-type="delete" data-url="{{ $actions['delete'] }}" title="{{ __('main.title_delete') }}" />
        @endif
    </x-slot:buttons>

    <div class="gallery js-body"></div>
</x-cms::ajaxlist>
