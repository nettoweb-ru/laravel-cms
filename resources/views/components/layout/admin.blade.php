@php
    \Netto\Services\AssetService::load('normalize', false);
    \Netto\Services\AssetService::load(['jquery', 'font.play']);
@endphp
@props([
    'head',
    'header' => '',
    'chain' => [],
    'url'
])
<!DOCTYPE html>
<html lang="{{ $head['language'] }}" xml:lang="{{ $head['language'] }}" dir="{{ $head['text_dir'] }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ $head['title'] }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    window.nettoweb = {
        lang: '{{ app()->getLocale() }}',
        locale: '{{ config('locale_js') }}',
        text_dir: '{{ config('text_dir') }}',
        upload_max_filesize: {{ ini_parse_quantity(ini_get('upload_max_filesize')) }},
        post_max_size: {{ ini_parse_quantity(ini_get('post_max_size')) }},
        messages: {
            btn_label_ok: '{{ __('main.action_ok') }}',
            btn_label_confirm: '{{ __('main.action_confirm') }}',
            btn_label_cancel: '{{ __('main.action_cancel') }}',
            btn_label_delete: '{{ __('main.action_delete') }}',
            confirm_toggle: '{{ __('main.confirmation_toggle') }}',
            confirm_logout: '{{ __('main.confirmation_logout') }}',
            confirm_delete: '{{ __('main.confirmation_delete') }}',
            error_upload_max: '{{ __('main.error_upload_max_size_exceeded') }}',
            error_post_max: '{{ __('main.error_post_max_size_exceeded') }}',
        }
    }

    let autocomplete = {}
</script>
@include('cms::components.assets')
@include('cms::components.favicons')
@vite([
    'resources/css/netto/layout.scss',
    'resources/css/netto/app.scss',
    'resources/js/netto/overlay.js',
    'resources/js/netto/ajax.js',
    'resources/js/netto/app.js',
])

@stack('head')
</head>
<body class="{{ $head['text_dir'] }}">
<div class="layer layer-content">
    <div class="block block-top">
        <div class="inline">
            <div class="table">
                <div class="cell left">
                    <div class="menu">
                        <x-cms-navigation :mode="2" />
                    </div>
                    <div class="icons">
                        <div class="icon icon-menu-open" id="js-mobile-menu-open"></div>
                        <div class="icon icon-menu-close hidden" id="js-mobile-menu-close"></div>
                    </div>
                </div>
                <div class="cell right">
                    <div class="icon icon-home js-link @if (request()->routeIs($url['home'])) active @endif" data-url="{{ route($url['home']) }}"></div>
                    <div class="icon icon-user js-link @if (request()->routeIs($url['profile'])) active @endif" data-url="{{ route($url['profile']) }}"></div>
                    <div class="icon icon-logout" id="js-logout"></div>
                    <div class="icon icon-language icon-desktop js-desktop-menu-show" data-id="lang"></div>
                    <div class="icon icon-language icon-mobile" id="js-mobile-languages-toggle"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="block block-main">
        <div class="inline">
            @if (!empty($chain))
                <div class="main main-navigation">
                    <div class="table">
                        <div class="cell">
                            <x-cms::navchain :items="$chain"/>
                        </div>
                    </div>
                </div>
            @endif
            <div class="main main-content">
                <div class="table main-content-table">
                    <div class="cell main-content-cell">
                        @if (!empty($header))
                            <div class="content-item header">
                                <span class="text-big">
                                    {{ $header }}
                                </span>
                            </div>
                        @endif
                        <div class="content-item content">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-cms-navigation :mode="3" />
<div class="layer layer-dropdown dropdown-{{ config('text_dir') == 'rtl' ? 'normal' : 'reversed' }} desktop languages js-desktop-menu" data-id="lang">
    <x-cms-languages/>
</div>
<div class="layer layer-dropdown mobile menu" id="js-mobile-menu">
    <x-cms-navigation />
</div>
<div class="layer layer-dropdown mobile languages" id="js-mobile-languages">
    <x-cms-languages/>
</div>
<x-cms::session-status :status="session('status')"/>
@stack('bottom')
<form method="post" action="{{ route($url['logout']) }}" id="js-logout-form">
    @csrf
</form>
</body>
</html>
