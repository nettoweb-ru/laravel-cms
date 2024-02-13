@props(['bg' => ''])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn']) }}>
    <div class="table table-btn">
        @if ($bg)
        <div class="cell cell-btn bg @if (!$slot->isEmpty()) align @endif">
            @include('cms::components.'.$bg)
        </div>
        @endif
        @if (!$slot->isEmpty())
            <div class="cell cell-btn text @if ($bg) padding @endif">
                <span class="text">{{ $slot }}</span>
            </div>
        @endif
    </div>
</button>
