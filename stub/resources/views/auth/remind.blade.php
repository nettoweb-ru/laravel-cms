<x-layout.default :title="$title">
    <form method="post" action="{{ route('password.email') }}">
        @csrf
        <x-cms::form.string name="email"
            :label="__('auth.email')"
            :value="old('email')"
            :messages="$errors->get('email')"
            :required="true"
            :autofocus="true"
        />
        <x-cms::form.button>{{ $title }}</x-cms::form.button>
    </form>
</x-layout.default>
