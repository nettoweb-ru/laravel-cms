@props(['title' => config('app.name') ])
<!DOCTYPE html>
<html lang="{{ config('locale') }}" xml:lang="{{ config('locale') }}" dir="{{ config('text_dir') }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $title }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="//code.jquery.com">
    <link href="//cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.css" rel="stylesheet" type="text/css">
    <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
    @vite([
        'resources/css/netto/layers.css',
        'resources/css/app.css',
        'resources/js/netto/overlay.js',
        'resources/js/netto/ajax.js',
        'resources/js/app.js',
    ])
</head>
<body class="{{ config('text_dir') }}">
<div class="wrapper">
    <div class="layer content">
        {{ $slot }}
    </div>
    @include('cms::components.overlay')
</div>
<x-cms::session-status :status="session('status')"/>
</body>
</html>
