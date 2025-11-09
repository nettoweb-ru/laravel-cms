@props([
    'name',
    'language' => get_default_language_code(),
    'id' => $name,
    'value' => '',
    'height' => 400,
])

@pushonce('head')
    @vite([
        'resources/css/styles.css',
        'resources/js/styles.js',
    ])
    <link rel="stylesheet" href="{{ asset('assets/css/editor.css') }}">
@endpushonce

@php \Netto\Services\AssetService::load('ckeditor', false) @endphp

<div class="editor h{{ $height }} js-editor" data-language="{{ $language }}">
    <div class="js-editor-object" id="{{ $id }}">{!! $value !!}</div>
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="js-editor-value" />
</div>
