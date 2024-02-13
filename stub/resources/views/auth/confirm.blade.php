<x-layout.default :title="$title">
    <form method="post" action="{{ route('password.confirm') }}">
        @csrf
        <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password_confirmation')" :messages="$errors->get('password')" autocomplete="current-password" required />
        <x-cms::form.button>{{ $title }}</x-cms::form.button>
    </form>
</x-layout.default>
