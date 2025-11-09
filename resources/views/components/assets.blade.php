@use('Netto\Services\AssetService')
<link rel="preconnect" href="{{ config('cms.assets.host') }}">
@foreach (AssetService::css() as $file => $deferred)
<link href="{{ config('cms.assets.host') }}/{{ $file }}" rel="stylesheet" @if ($deferred) media="print" @endif type="text/css" />
@endforeach
@foreach (AssetService::js() as $file => $deferred)
<script @if ($deferred) defer @endif src="{{ config('cms.assets.host') }}/{{ $file }}"></script>
@endforeach
<script>
    window.onload = function() {
        let links = document.getElementsByTagName('link'), key
        for (key in links) {
            if (links[key].media === 'print') {
                links[key].media = 'all'
            }
        }
    }
</script>
