<x-cms::layout.guest :head="$head">
    <x-cms-form :url="['save' => route('admin.password.confirm')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
            <x-cms::form.string name="password" type="password" width="6" autocomplete="current-password"
                :label="__('auth.password_confirmation')"
                :messages="$errors->get('password')"
                :required="true"
            />
        </x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ $btnTitle }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
</x-cms::layout.guest>
