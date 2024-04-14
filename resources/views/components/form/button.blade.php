@props(['bg' => ''])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn']) }}>
    <span class="table table-btn">
        @if ($bg)
        <span class="cell cell-btn bg @if (!$slot->isEmpty()) align @endif">
            @include('cms::components.'.$bg)
        </span>
        @endif
        @if (!$slot->isEmpty())
            <span class="cell cell-btn text @if ($bg) padding @endif">
                <span class="text">{{ $slot }}</span>
            </span>
        @endif
    </span>
</button>
