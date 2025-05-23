@props([
    'messages'
])

@if ($messages)
    <div class="grid-item errors">
        @foreach ($messages as $message)
            <div class="grid-error js-form-error">
                <div class="table">
                    <div class="cell">
                        <span class="text-small">{{ $message }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
