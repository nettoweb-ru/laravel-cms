<x-layout.default :title="$title">
    <form method="post" action="{{ route('login') }}">
        @csrf
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
        <x-cms::form.button>{{ __('auth.action_login') }}</x-cms::form.button>
        <p>
            <a href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
        </p>
    </form>
</x-layout.default>
