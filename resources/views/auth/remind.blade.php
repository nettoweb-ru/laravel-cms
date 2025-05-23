<x-cms::layout.guest :head="$head">
    <x-cms-form :url="['save' => route('admin.password.email')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
            <x-cms::form.string name="email"
                :label="__('auth.email')"
                :value="old('email')"
                :messages="$errors->get('email')"
                :required="true"
                :autofocus="true"
            />
        </x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ $btnTitle }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
</x-cms::layout.guest>
