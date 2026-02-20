<x-cms::layout.guest :head="$head">
    <x-cms-form :url="['save' => route('admin.password.store')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
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
        </x-slot>
        <x-slot name="buttons">
            <button class="btn btn-blue btn-label">{{ $btnTitle }}</button>
        </x-slot>
    </x-cms-form>
</x-cms::layout.guest>
