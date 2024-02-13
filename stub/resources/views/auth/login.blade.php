<x-layout.default :title="$title">
    <form method="post" action="{{ route('login') }}">
        @csrf
        <x-cms::form.string name="email" type="text" :label="__('cms::auth.email')" :value="old('email')" :messages="$errors->get('email')" required autofocus autocomplete="username" />
        <x-cms::form.string name="password" type="password" :label="__('cms::auth.password')" :messages="$errors->get('password')" required autocomplete="current-password" />
        <x-cms::form.checkbox name="remember" :options="['Y' => __('cms::auth.remember_me')]" />

        <x-cms::form.button>{{ __('cms::auth.action_login') }}</x-cms::form.button>
        <p>
            <a href="{{ route('password.request') }}">{{ __('cms::auth.forgot_password') }}</a>
        </p>
    </form>
</x-layout.default>
