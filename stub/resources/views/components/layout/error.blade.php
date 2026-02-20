@php
    \Netto\Services\AssetService::load('normalize', false);
    set_language_default();
@endphp
@props([
    'title',
    'link' => '',
    'language' => app()->getLocale(),
    'text_dir' => config('text_dir'),
])
<!DOCTYPE html>
<html lang="{{ $language }}" xml:lang="{{ $language }}" dir="{{ $text_dir }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ __($title) }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@include('cms::components.assets')
@include('components.favicons')
@vite([
    'resources/css/error.scss',
])

</head>
<body class="{{ $text_dir }}">
<div class="layer layer-content">
    <div class="error-table">
        <div class="error-cell">
            <div class="inline">
                <div class="error-block logo">
                    <div class="logo">
                        @include('cms::components.icons.logo')
                    </div>
                </div>
                <div class="error-block message">
                    <span>{{ __($title) }}</span>
                    {{ $slot }}
                </div>
                @if ($link)
                    <div class="error-block link">
                        <span><a href="{{ route('ru.home') }}">{{ __('errors.return_to_main') }}</a></span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</body>
</html>
