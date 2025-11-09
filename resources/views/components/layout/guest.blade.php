@php
    \Netto\Services\AssetService::load('normalize', false);
    \Netto\Services\AssetService::load(['jquery', 'font.play']);
@endphp
@props([
    'head',
    'content',
])
<!DOCTYPE html>
<html lang="{{ $head['language'] }}" xml:lang="{{ $head['language'] }}" dir="{{ $head['text_dir'] }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ $head['title'] }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('cms::components.assets')
@include('cms::components.favicons')
@vite([
    'resources/css/netto/layers.css',
    'resources/css/netto/buttons.css',
    'resources/css/netto/guest.css',
    'resources/js/netto/overlay.js',
])

@stack('head')
</head>
<body class="{{ $head['text_dir'] }}">
    <div class="wrapper">
        <div class="layer content">
            <div class="table container-table">
                <div class="cell container-cell">
                    <div class="inline">
                        <div class="guest-logo">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                 y="0px" viewBox="0 0 323.41 62.74" xml:space="preserve">
                            @include('cms::components.icons.logo')
                        </svg>
                        </div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @include('cms::components.overlay')
    </div>
<x-cms::session-status :status="session('status')"/>
@stack('bottom')
</body>
</html>
