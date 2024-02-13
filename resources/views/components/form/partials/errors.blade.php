@props(['messages', 'multilang' => false])

@if ($messages)
    <div class="grid-item errors">
        @if ($multilang)
            @foreach ($messages as $langCode => $langMessages)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    @foreach ($langMessages as $message)
                        <div class="grid-error js-form-error">
                            <div class="table">
                                <div class="cell">
                                    <span class="text-small">{{ $message }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @else
            @foreach ($messages as $message)
                <div class="grid-error js-form-error">
                    <div class="table">
                        <div class="cell">
                            <span class="text-small">{{ $message }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endif
