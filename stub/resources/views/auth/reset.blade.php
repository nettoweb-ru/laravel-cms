<x-layout.default :title="$title">
    <form method="post" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-cms::form.string name="email" type="text" :label="__('cms::auth.email')" :value="old('email', $request->email)" :messages="$errors->get('email')" required autofocus />
        <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password')" :messages="$errors->get('password')" autocomplete="new-password" required />
        <x-cms::form.string name="password_confirmation" type="password" width="6" :label="__('cms::auth.password_confirmation')" :messages="$errors->get('password_confirmation')" autocomplete="new-password" />

        <x-cms::form.button>{{ $title }}</x-cms::form.button>
    </form>
</x-layout.default>
