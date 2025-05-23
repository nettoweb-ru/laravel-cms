@extends('cms::mail.layout')

@section('content')
    <p style="font-size:16px;line-height:1.4em;margin-top:0;">
        <span style="font-weight: 700;">{{ $header }}</span><br />
        <span style="font-size: 13px;">{{ $name }}<br />><a style="color:#2a3582;word-break:break-all;" href="{{ $url }}" target="_blank">{{ $url }}</a></span>
    </p>

    <p style="font-size: 13px;line-height:1.4em;">
        {{ $date }}
    </p>

    @foreach ($versions as $package => $version)
        <p style="font-size:13px;line-height:1.4em;margin-top:0;">
            {{ $package }}<br />
            <span style="color:#777;font-style: italic;">{{ $version }}</span>
        </p>
    @endforeach
@endsection
