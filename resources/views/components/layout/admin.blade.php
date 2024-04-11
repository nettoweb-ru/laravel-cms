@props(['title' => config('app.name'), 'header' => '', 'chain' => []])
<!DOCTYPE html>
<html lang="{{ config('locale') }}" xml:lang="{{ config('locale') }}" dir="{{ config('text_dir') }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $title }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="preconnect" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="//code.jquery.com">
    <link href="//fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet" type="text/css">
    <link href="//cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.css" rel="stylesheet" type="text/css">
    <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
    @include('cms::components.favicons')
    @vite([
        'resources/css/netto/layers.css',
        'resources/css/netto/buttons.css',
        'resources/css/netto/admin.css',
        'resources/js/netto/overlay.js',
        'resources/js/netto/ajax.js',
        'resources/js/netto/app.js',
    ])
    <script>
        let autocomplete = {}
        $(document).ready(function () {
            App.lang = '{{ app()->getLocale() }}'
            App.messages.confirm.toggle = '{{ __('cms::main.confirmation_toggle') }}'
            App.messages.confirm.logout = '{{ __('cms::main.confirmation_logout') }}'
            App.messages.labels.delete = '{{ __('cms::main.action_delete') }}'
            App.messages.errors.uploadMaxFileSize = '{{ __('cms::main.error_upload_max_size_exceeded') }}'
            App.messages.errors.postMaxSize = '{{ __('cms::main.error_post_max_size_exceeded') }}'
        })
    </script>
    @stack('head')
</head>
<body class="{{ config('text_dir') }}">
<div class="wrapper">
    <div class="layer content">
        <div class="inline">
            <div class="block mobile">
                <div class="table">
                    <div class="cell left">
                        <div class="mobile-icon menu-open" id="js-icon-menu-open">
                            @include('cms::components.icons.menu')
                        </div>
                        <div class="mobile-icon menu-close" id="js-icon-menu-close">
                            @include('cms::components.icons.close')
                        </div>
                    </div>
                    <div class="cell center">
                        @if (!empty($chain))
                            <x-cms::navchain :items="$chain"/>
                        @endif
                    </div>
                    <div class="cell right">
                        <div class="mobile-icon languages" id="js-icon-languages">
                            @include('cms::components.icons.hiragana-ma')
                        </div>
                    </div>
                </div>
            </div>
            <div class="block dropdown menu" id="js-mobile-menu">
                <x-cms-navigation />
            </div>
            <div class="block dropdown languages" id="js-mobile-languages">
                <x-cms-languages />
            </div>
            <div class="block content">
                <div class="block desktop">
                    <div class="block menu">
                        <div class="table desktop-menu-table">
                            <div class="cell desktop-menu-cell left">
                                <x-cms-navigation />
                            </div>
                            <div class="cell desktop-menu-cell right">

                                <div class="menu">
                                    <div class="menu-item">
                                        <div class="menu-item-block title">
                                            <div class="icon">
                                                @include('cms::components.icons.hiragana-ma')
                                            </div>
                                        </div>
                                        <div class="menu-item-block dropdown">
                                            <x-cms-languages />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @if (!empty($chain))
                        <div class="block chain">
                            <div class="table desktop-chain-table">
                                <div class="cell desktop-chain-cell">
                                    <x-cms::navchain :items="$chain"/>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="block main">
                    <div class="table main-padding-table">
                        <div class="cell main-padding-cell">
                            @if (!empty($header))
                                <p class="header text-big">
                                    {{ $header }}
                                </p>
                            @endif
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('cms::components.overlay')
</div>
<x-cms::session-status :status="session('status')"/>
</body>
</html>
