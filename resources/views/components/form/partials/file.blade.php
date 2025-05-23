@props([
    'name',
    'id' => $name,
    'disabled' => false,
    'value' => '',
    'image' => '',
])

@php
if ($value && str_starts_with(DIRECTORY_SEPARATOR.$value, get_storage_path('public')) && in_array(\Illuminate\Support\Facades\File::mimeType(base_path($value)), [
    'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'
])) {
    \Netto\Services\CDNService::load('fancybox');
    $image = get_public_uploaded_path($value);
}
@endphp

<div class="grid-value-file js-file-attr">
    <div class="cell filename">
        <input type="text" value="{{ $value }}" id="{{ $id }}-visible" disabled {!! $attributes->merge(['class' => 'input text js-file-text disabled'])->toHtml() !!} />
        <input type="hidden" name="{{ $name }}" class="js-file-value" value="{{ $value }}" />
        <input type="file" name="{{ $name }}|new" id="{{ $id }}" class="hidden js-file-input" />
    </div>
    <div class="cell icons">
        @if ($image)
            <a data-fancybox data-src="{{ $image }}">
                <x-cms::form.button type="button" bg="icons.image" class="btn-icon btn-normal js-view-image" title="{{ __('main.title_view_image') }}" />
            </a>
        @endif
        <x-cms::form.button type="button" bg="icons.upload" :class="'btn-icon btn-normal '.($disabled ? 'disabled' : 'js-file-upload')" title="{{ __('main.title_upload_file_new') }}" />
        @if ($value)
            <x-cms::form.button type="button" bg="icons.download" class="btn-icon btn-normal js-file-download" data-filename="{{ DIRECTORY_SEPARATOR.$value }}" title="{{ __('main.title_upload_file_download') }}" />
            <x-cms::form.button type="button" bg="icons.remove" :class="'btn-icon btn-warning '.($disabled ? 'disabled' : 'js-file-delete')" data-status="0" data-filename="{{ $value }}" title="{{ __('main.title_upload_file_delete') }}" />
        @else
            <x-cms::form.button type="button" bg="icons.download" class="btn-icon btn-normal disabled" />
            <x-cms::form.button type="button" bg="icons.remove" class="btn-icon btn-warning disabled" />
        @endif
    </div>
</div>
