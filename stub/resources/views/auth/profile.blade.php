<x-layout.default :title="$title">
    {{ __('public.my_profile') }}

    {{ __('auth.profile') }}

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <x-cms::form.string name="name" width="6" maxlength="255"
            :label="__('auth.name')"
            :value="old('name', $object->getAttribute('name'))"
            :messages="$errors->get('name')"
            :required="true"
            :autofocus="true"
        />
        <x-cms::form.string name="email" width="6" maxlength="255"
            :label="__('auth.email')"
            :value="old('email', $object->getAttribute('email'))"
            :messages="$errors->get('email')"
            :required="true"
        />
        <button>{{ __('auth.action_save') }}</button>
    </form>

    {{ __('auth.update_password') }}

    <form method="post" action="{{ route('profile.password.update') }}">
        @csrf
        @method('put')

        <x-cms::form.string name="current_password" type="password" autocomplete="current-password"
            :label="__('auth.password_current')"
            :messages="$errors->updatePassword->get('current_password')"
            :required="true"
        />
        <x-cms::form.string name="password" type="password" width="6" autocomplete="new-password"
            :label="__('auth.password_new')"
            :messages="$errors->updatePassword->get('password')"
            :required="true"
        />
        <x-cms::form.string name="password_confirmation" type="password" width="6" autocomplete="new-password"
            :label="__('auth.password_confirmation')"
            :messages="$errors->updatePassword->get('password_confirmation')"
        />

        <button>{{ __('auth.action_save') }}</button>
    </form>

    {{ __('auth.delete_profile') }}

    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <x-cms::form.string name="password" type="password" width="6" autocomplete="current-password"
            :label="__('auth.password_current')"
            :messages="$errors->userDeletion->get('password')"
            :required="true"
        />

        <button>{{ __('auth.action_delete') }}</button>
    </form>

    <p>
        <a href="{{ route('personal') }}"><< {{ __('public.personal') }}</a>
    </p>
</x-layout.default>
