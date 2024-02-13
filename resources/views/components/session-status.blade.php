@props(['status'])

@if ($status)
    <div class="hidden js-flash-message">{{ $status }}</div>
@endif
