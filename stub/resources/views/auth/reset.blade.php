<x-layout.default :title="$title">
    <form method="post" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-cms::form.string name="email"
            :label="__('auth.email')"
            :value="old('email', $request->email)"
            :messages="$errors->get('email')"
            :required="true"
            :autofocus="true"
        />
        <x-cms::form.string name="password" type="password" autocomplete="new-password"
            :label="__('auth.password')"
            :messages="$errors->get('password')"
            :required="true"
        />
        <x-cms::form.string name="password_confirmation" type="password" autocomplete="new-password"
            :label="__('auth.password_confirmation')"
            :messages="$errors->get('password_confirmation')"
        />

        <button>{{ $title }}</button>
    </form>
</x-layout.default>
