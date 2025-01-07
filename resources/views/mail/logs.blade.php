<p>
    <strong>{{ $appName }}</strong><br />
    <a href="{{ $appUrl }}" target="_blank">{{ $appUrl }}</a>
</p>

<p>
    laravel-cms: {{ $vCore }}<br />
@if ($vCurrency)
    laravel-cms-currency: {{ $vCurrency }}<br />
@endif
@if ($vStore)
    laravel-cms-store: {{ $vStore }}
@endif
</p>

<p>
    {{ $date }}
</p>
