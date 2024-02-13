<x-cms::layout.guest :title="$title">
    <x-cms-form :url="['save' => route('admin.password.confirm')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
            <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password_confirmation')" :messages="$errors->get('password')" autocomplete="current-password" required />
        </x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ $title }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
</x-cms::layout.guest>
