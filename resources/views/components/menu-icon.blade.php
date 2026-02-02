@props([
    'icon',
    'route' => '',
    'dropdown' => null,
    'id' => '',
    'title' => '',
])

@php
    $active = $route && request()->routeIs($route);
    $link = $route && !$active;
@endphp

<div title="{{ $title }}" class="menu-item @if ($active) active @endif @if ($link) js-link @endif " @if ($id) id="{{ $id }}" @endif @if ($link) data-url="{{ route($route) }}" @endif>
    <div class="menu-item-block title">
        <span class="icon">
            @include('cms::components.icons.'.$icon)
        </span>
    </div>
    @if ($dropdown)
        {!! $dropdown->toHtml() !!}
    @endif
</div>
