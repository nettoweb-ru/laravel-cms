@props([
    'icon',
    'route' => '',
    'id' => '',
])

@php
    $link = $route && !request()->routeIs($route);
@endphp

<div class="mobile-icon @if ($link) js-top-link @endif" @if ($id) id="{{ $id }}" @endif @if ($link) data-url="{{ route($route) }}" @endif>
    @include('cms::components.icons.'.$icon)
</div>
