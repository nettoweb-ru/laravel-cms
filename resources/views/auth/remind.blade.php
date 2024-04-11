<x-cms::layout.guest :title="$title">
    <div class="guest-logo">
        @include('cms::components.icons.logo')
    </div>
    <x-cms-form :url="['save' => route('admin.password.email')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1">
            <x-cms::form.string name="email" type="text" :label="__('cms::auth.email')" :value="old('email')" :messages="$errors->get('email')" required autofocus />
        </x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ $title }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
</x-cms::layout.guest>
