@extends('cms::mail.layout')

@section('content')
    <p style="font-size:16px;line-height:1.4em;margin-top:0;text-align:center;">
        {{ __('auth.email_verify_email_message') }}
    </p>

    <p style="font-size:13px;line-height:1.4em;margin-top:0;text-align:center;">
        <a href="{{ $url }}" target="_blank" style="color:#2a3582;word-break:break-all;">{{ $url }}</a>
    </p>
@endsection
