<x-layout.default :title="$title">
    <form method="post" action="{{ route('verification.send') }}">
        @csrf
        <button>{{ $title }}</button>
    </form>
</x-layout.default>
