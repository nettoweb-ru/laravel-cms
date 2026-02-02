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
    'resources/css/netto/layers.css',
    'resources/css/netto/buttons.css',
    'resources/css/netto/admin.css',
    'resources/js/netto/overlay.js',
    'resources/js/netto/ajax.js',
    'resources/js/netto/app.js',
])

@stack('head')
</head>
<body class="{{ $head['text_dir'] }}">
<div class="layer content">
    <div class="block mobile-top">
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
                <x-cms::menu-icon-mobile icon="home" route="{{ $url['home'] }}" />
                <x-cms::menu-icon-mobile icon="user" route="{{ $url['profile'] }}" />
                <x-cms::menu-icon-mobile icon="logout-{{ config('text_dir') }}" id="js-logout-mobile" />
                <x-cms::menu-icon-mobile icon="hiragana-ma" id="js-icon-languages" />
            </div>
        </div>
    </div>
    <div class="block mobile-menu" id="js-mobile-menu">
        <x-cms-navigation/>
    </div>
    <div class="block mobile-languages" id="js-mobile-languages">
        <x-cms-languages/>
    </div>
    <div class="block content">
        <div class="inline">
            <div class="block menu">
                <div class="table">
                    <div class="cell left">
                        <x-cms-navigation/>
                    </div>
                    <div class="cell right">
                        <div class="menu">
                            <x-cms::menu-icon icon="home" route="{{ $url['home'] }}" title="{{ __('main.general_home') }}" />
                            <x-cms::menu-icon icon="user" route="{{ $url['profile'] }}" title="{{ __('auth.profile') }}" />
                            <x-cms::menu-icon icon="logout-{{ config('text_dir') }}" id="js-logout" title="{{ __('auth.logout') }}" />
                            <x-cms::menu-icon icon="hiragana-ma">
                                <x-slot:dropdown>
                                    <div class="menu-item-block dropdown">
                                        <x-cms-languages/>
                                    </div>
                                </x-slot:dropdown>
                            </x-cms::menu-icon>
                        </div>
                    </div>
                </div>
            </div>
            @if (!empty($chain))
                <div class="block chain-hold">
                    <div class="table">
                        <div class="cell">
                            <x-cms::navchain :items="$chain"/>
                        </div>
                    </div>
                </div>
            @endif
            <div class="block main">
                <div class="table main-padding-table">
                    <div class="cell main-padding-cell">
                        @if (!empty($header))
                            <p class="header text-big">
                                {{ $header }}
                            </p>
                        @endif
                        <div>
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-cms::session-status :status="session('status')"/>
@stack('bottom')
<form method="post" action="{{ route($url['logout']) }}" id="js-logout-form">
    @csrf
</form>
</body>
</html>
