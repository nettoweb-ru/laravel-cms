<x-cms::layout.guest :title="$title">
    <x-cms-form :url="['save' => route('admin.register')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
            <x-cms::form.string name="name" type="text" width="6" maxlength="255" :label="__('cms::auth.name')" :value="old('name')" :messages="$errors->get('name')" required autofocus />
            <x-cms::form.string name="email" type="text" width="6" maxlength="255" :label="__('cms::auth.email')" :value="old('email')" :messages="$errors->get('email')" required />
            <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password')" :messages="$errors->get('password')" autocomplete="new-password" required />
            <x-cms::form.string name="password_confirmation" type="password" width="6" :label="__('cms::auth.password_confirmation')" :messages="$errors->get('password_confirmation')" autocomplete="new-password" />
        </x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ $title }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
</x-cms::layout.guest>
