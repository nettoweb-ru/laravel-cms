<x-layout.default :title="$title">
    {{ __('public.my_profile') }}

    {{ __('cms::auth.profile') }}

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <x-cms::form.string name="name" type="text" width="6" maxlength="255" :label="__('cms::auth.name')" :value="old('name', $object->name)" :messages="$errors->get('name')" required autofocus />
        <x-cms::form.string name="email" type="text" width="6" maxlength="255" :label="__('cms::auth.email')" :value="old('email', $object->email)" :messages="$errors->get('email')" required />

        <x-cms::form.button>{{ __('cms::main.action_save') }}</x-cms::form.button>
    </form>

    {{ __('cms::auth.update_password') }}

    <form method="post" action="{{ route('profile.password.update') }}">
        @csrf
        @method('put')

        <x-cms::form.string name="current_password" type="password" :label="__('cms::auth.password_current')" :messages="$errors->updatePassword->get('current_password')" autocomplete="current-password" required autofocus />
        <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password_new')" :messages="$errors->updatePassword->get('password')" autocomplete="new-password" required />
        <x-cms::form.string name="password_confirmation" type="password" width="6" :label="__('cms::auth.password_confirmation')" :messages="$errors->updatePassword->get('password_confirmation')" autocomplete="new-password" />

        <x-cms::form.button>{{ __('cms::main.action_save') }}</x-cms::form.button>
    </form>

    {{ __('cms::auth.delete_profile') }}

    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password_current')" :messages="$errors->userDeletion->get('password')" autocomplete="current-password" required />

        <x-cms::form.button>{{ __('cms::main.action_delete') }}</x-cms::form.button>
    </form>

    <p>
        <a href="{{ route('personal') }}"><< {{ __('public.personal') }}</a>
    </p>
</x-layout.default>
