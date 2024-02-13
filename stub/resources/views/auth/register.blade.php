<x-layout.default :title="$title">
    <form method="post" action="{{ route('register') }}">
        @csrf
        <x-cms::form.string name="name" type="text" width="6" maxlength="255" :label="__('cms::auth.name')" :value="old('name')" :messages="$errors->get('name')" required autofocus />
        <x-cms::form.string name="email" type="text" width="6" maxlength="255" :label="__('cms::auth.email')" :value="old('email')" :messages="$errors->get('email')" required />
        <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password')" :messages="$errors->get('password')" autocomplete="new-password" required />
        <x-cms::form.string name="password_confirmation" type="password" width="6" :label="__('cms::auth.password_confirmation')" :messages="$errors->get('password_confirmation')" autocomplete="new-password" />

        <x-cms::form.button>{{ $title }}</x-cms::form.button>
    </form>
</x-layout.default>
