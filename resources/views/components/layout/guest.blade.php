@props(['title' => config('app.name') ])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" xml:lang="{{ app()->getLocale() }}" dir="{{ config('text_dir') }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $title }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php load_cdn_resources(['normalize', 'play', 'jquery'], true) @endphp
    @include('cms::components.favicons')
    @vite([
        'resources/css/netto/layers.css',
        'resources/css/netto/buttons.css',
        'resources/css/netto/guest.css',
        'resources/js/netto/overlay.js',
    ])
    @stack('head')
</head>
<body class="{{ config('text_dir') }}">
<div class="wrapper">
    <div class="layer content">
        <div class="table container-table">
            <div class="cell container-cell">
                <div class="inline">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    @include('cms::components.overlay')
</div>
<x-cms::session-status :status="session('status')"/>
</body>
</html>
