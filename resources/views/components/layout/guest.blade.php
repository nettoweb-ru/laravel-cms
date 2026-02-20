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
    'resources/css/netto/layout.scss',
    'resources/css/netto/guest.scss',
    'resources/js/netto/overlay.js',
])

@stack('head')

<script>
    window.nettoweb = {
        messages: {
            btn_label_ok: '{{ __('main.action_ok') }}',
        }
    }
</script>
</head>

<body class="{{ $head['text_dir'] }}">
<div class="layer layer-content">
    <div class="table container-table">
        <div class="cell container-cell">
            <div class="inline">
                <div class="guest-logo">
                    @include('cms::components.icons.logo')
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
<x-cms::session-status :status="session('status')"/>
@stack('bottom')
</body>
</html>
