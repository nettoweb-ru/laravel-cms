<x-cms::layout.guest :title="$title">
    <div class="guest-logo">
        @include('cms::components.icons.logo')
    </div>
    <x-cms-form :url="['save' => route('admin.password.store')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <x-cms::form.string name="email" type="text" :label="__('cms::auth.email')" :value="old('email', $request->email)" :messages="$errors->get('email')" required autofocus />
            <x-cms::form.string name="password" type="password" :label="__('cms::auth.password')" :messages="$errors->get('password')" autocomplete="new-password" required />
            <x-cms::form.string name="password_confirmation" type="password" :label="__('cms::auth.password_confirmation')" :messages="$errors->get('password_confirmation')" autocomplete="new-password" />
        </x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ $title }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
</x-cms::layout.guest>
