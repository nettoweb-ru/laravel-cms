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
    'resources/css/netto/layers.css',
    'resources/css/error.css',
])

</head>
<body class="{{ $text_dir }}">
<div class="wrapper">
    <div class="layer content">
        <div class="error-table">
            <div class="error-cell">
                <div class="inline">
                    <div class="error-block logo">
                        <div class="logo">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 323.41 62.74" xml:space="preserve">
                                @include('cms::components.icons.logo')
                            </svg>
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
</div>
</body>
</html>
