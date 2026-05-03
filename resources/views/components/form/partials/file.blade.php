@props([
    'disk',
    'name',
    'id' => $name,
    'disabled' => false,
    'value' => '',
    'image' => '',
])

@php
    if ($value && in_array(\Illuminate\Support\Facades\File::mimeType(get_storage_path($value, $disk)), [
        'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'
    ])) {
        \Netto\Services\AssetService::load('fancybox', false);
        $image = get_storage_url($value, $disk);
    }
@endphp

<div class="grid-value-file table js-file-attr">
    <div class="cell filename">
        <input type="text" value="{{ $value }}" id="{{ $id }}-visible" disabled {!! $attributes->merge(['class' => 'input text js-file-text disabled'])->toHtml() !!} >
        <input type="hidden" name="{{ $name }}" class="js-file-value" value="{{ $value }}">
        <input type="file" name="{{ $name }}|new" id="{{ $id }}" class="hidden js-file-input">
    </div>
    <div class="cell icons">
        @if ($image)
            <a data-fancybox data-src="{{ $image }}">
                <button type="button" class="btn btn-bg btn-blue image js-view-image" title="{{ __('main.title_view_image') }}"></button>
            </a>
        @endif
        <button type="button" class="btn btn-bg btn-blue upload @if ($disabled) disabled @else js-file-upload @endif" title="{{ __('main.title_upload_file_new') }}"></button>
        @if ($value)
            <button type="button" class="btn btn-bg btn-blue download js-file-download"
                    data-filename="{{ DIRECTORY_SEPARATOR.$value }}"
                    data-disk="{{ $disk }}"
                    title="{{ __('main.title_upload_file_download') }}"></button>
            <button type="button" class="btn btn-bg btn-red remove @if ($disabled) disabled @else js-file-delete @endif" data-status="0" data-filename="{{ $value }}" title="{{ __('main.title_upload_file_delete') }}"></button>
        @endif
    </div>
</div>
