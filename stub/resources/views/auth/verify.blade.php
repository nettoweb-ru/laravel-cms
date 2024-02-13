<x-layout.default :title="$title">
    <form method="post" action="{{ route('verification.send') }}">
        @csrf
        <x-cms::form.button>{{ $title }}</x-cms::form.button>
    </form>
</x-layout.default>
