<x-layout.default :title="$title">
    <form method="post" action="{{ route('password.confirm') }}">
        @csrf
        <x-cms::form.string name="password" type="password" width="6" autocomplete="current-password"
            :label="__('auth.password_confirmation')"
            :messages="$errors->get('password')"
            :required="true"
        />
        <x-cms::form.button>{{ $title }}</x-cms::form.button>
    </form>
</x-layout.default>
