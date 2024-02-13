<x-layout.default :title="$title">
    <form method="post" action="{{ route('password.email') }}">
        @csrf
        <x-cms::form.string name="email" type="text" :label="__('cms::auth.email')" :value="old('email')" :messages="$errors->get('email')" required autofocus />
        <x-cms::form.button>{{ $title }}</x-cms::form.button>
    </form>
</x-layout.default>
