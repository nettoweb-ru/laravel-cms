<x-cms::layout.guest :title="$title">
    <div class="guest-logo">
        @include('cms::components.icons.logo')
    </div>
    <x-cms-form :url="['save' => route('admin.login.store')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
            <x-cms::form.string name="email" type="text" :label="__('cms::auth.email')" :value="old('email')" :messages="$errors->get('email')" required autofocus autocomplete="username" />
            <x-cms::form.string name="password" type="password" :label="__('cms::auth.password')" :messages="$errors->get('password')" required autocomplete="current-password" />
            <x-cms::form.checkbox name="remember" :options="['Y' => __('cms::auth.remember_me')]" />
        </x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ __('cms::auth.action_login') }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
    <p class="text">
        <a href="{{ route('admin.password.request') }}" class="js-animated-link">{{ __('cms::auth.forgot_password') }}</a>
    </p>
</x-cms::layout.guest>
