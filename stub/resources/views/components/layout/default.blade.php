@php
\Netto\Services\AssetService::load('normalize', false);
\Netto\Services\AssetService::load('jquery');
@endphp
@props([
    'head',
    'content',
    'chain' => [],
    'og_image' => [],
])
<!DOCTYPE html>
<html lang="{{ $head['language'] }}" xml:lang="{{ $head['language'] }}" dir="{{ $head['text_dir'] }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ $head['title'] }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@if (!empty($head['meta_description']))
<meta name="description" content="{{ $head['meta_description'] }}" />
@endif
@if (!empty($head['meta_keywords']))
<meta name="keywords" content="{{ $head['meta_keywords'] }}" />
@endif
@if (!empty($head['og_title']) || !empty($head['og_description']))
<meta property="og:title" content="{{ $head['og_title'] }}" />
<meta property="og:description" content="{{ $head['og_description'] }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ $head['canonical'] }}" />
<meta property="og:locale" content="{{ $head['locale'] }}" />
@endif
@if ($og_image)
<meta property="og:image" content="{{ $og_image['path'] }}" />
<meta property="og:image:type" content="{{ $og_image['type'] }}" />
<meta property="og:image:width" content="{{ $og_image['width'] }}" />
<meta property="og:image:height" content="{{ $og_image['height'] }}" />
@endif
<link rel="canonical" href="{{ $head['canonical'] }}" />
@if (!empty($head['alternate']))
@foreach ($head['alternate'] as $item)
<link rel="alternate" hreflang="{{ $item['locale'] }}" href="{{ $item['link'] }}" />
@endforeach
@endif
@include('cms::components.assets')
@include('components.favicons')
@vite([
    'resources/css/app.css',
    'resources/js/netto/overlay.js',
    'resources/js/netto/ajax.js',
    'resources/js/app.js',
])

@stack('head')
</head>
<body class="{{ $head['text_dir'] }}">
<div class="layer content">
    {{ $slot }}
</div>
<x-cms::session-status :status="session('status')"/>
@stack('bottom')
@include('components.shared')
</body>
</html>
