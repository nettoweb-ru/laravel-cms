<x-cms::layout.guest :head="$head">
    <x-cms-form :url="['save' => route('admin.login.store')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
            <x-cms::form.string name="email" autocomplete="username"
                :label="__('auth.email')"
                :value="old('email')"
                :messages="$errors->get('email')"
                :required="true"
                :autofocus="true"
            />
            <x-cms::form.string name="password" type="password" autocomplete="current-password"
                :label="__('auth.password')"
                :messages="$errors->get('password')"
                :required="true"
            />
            <x-cms::form.checkbox name="remember" type="checkbox"
                :options="['Y' => __('auth.remember_me')]"
            />
        </x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ __('auth.action_login') }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
    <p class="text">
        <a href="{{ route('admin.password.request') }}">{{ __('auth.forgot_password') }}</a>
    </p>
</x-cms::layout.guest>
