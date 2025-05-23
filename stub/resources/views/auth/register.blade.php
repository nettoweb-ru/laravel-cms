<x-layout.default :title="$title">
    <form method="post" action="{{ route('register') }}">
        @csrf
        <x-cms::form.string name="name" width="6" maxlength="255"
            :label="__('auth.name')"
            :value="old('name')"
            :messages="$errors->get('name')"
            :required="true"
            :autofocus="true"
        />
        <x-cms::form.string name="email" width="6" maxlength="255"
            :label="__('auth.email')"
            :value="old('email')"
            :messages="$errors->get('email')"
            :required="true"
        />
        <x-cms::form.string name="password" type="password" width="6" autocomplete="new-password"
            :label="__('auth.password')"
            :messages="$errors->get('password')"
            :required="true"
        />
        <x-cms::form.string name="password_confirmation" type="password" width="6" autocomplete="new-password"
            :label="__('auth.password_confirmation')"
            :messages="$errors->get('password_confirmation')"
        />

        <x-cms::form.button>{{ $title }}</x-cms::form.button>
    </form>
</x-layout.default>
