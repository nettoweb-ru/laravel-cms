{{ $appName }}
{{ $appUrl }}

laravel-cms: {{ $vCore }}
@if ($vCurrency)laravel-cms-currency: {{ $vCurrency }}
@endif
@if ($vStore)laravel-cms-store: {{ $vStore }}
@endif

{{ $date }}
