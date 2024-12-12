<x-cms::layout.admin :title="$title" :header="$header">
    <x-cms::tabs id="profile_tab" :tabs="[1 => 'cms::auth.profile', 2 => 'cms::auth.update_password']">
        <x-slot name="tab1">
            <x-cms-form :url="['save' => route('admin.profile.update')]" method="patch" :objectId="$object->id" :apply="false">
                <x-slot name="sheet1">
                    <x-cms::form.string name="name" type="text" width="6" maxlength="255" :label="__('cms::auth.name')" :value="old('name', $object->name)" :messages="$errors->get('name')" required autofocus />
                    <x-cms::form.string name="email" type="text" width="6" maxlength="255" :label="__('cms::auth.email')" :value="old('email', $object->email)" :messages="$errors->get('email')" required />
                </x-slot>
            </x-cms-form>
        </x-slot>
        <x-slot name="tab2">
            <x-cms-form :url="['save' => route('admin.profile.password.update')]" method="put" :objectId="$object->id" :apply="false">
                <x-slot name="sheet1">
                    <x-cms::form.string name="current_password" type="password" :label="__('cms::auth.password_current')" :messages="$errors->updatePassword->get('current_password')" autocomplete="current-password" required autofocus />
                    <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password_new')" :messages="$errors->updatePassword->get('password')" autocomplete="new-password" required />
                    <x-cms::form.string name="password_confirmation" type="password" width="6" :label="__('cms::auth.password_confirmation')" :messages="$errors->updatePassword->get('password_confirmation')" autocomplete="new-password" />
                </x-slot>
            </x-cms-form>
        </x-slot>
    </x-cms::tabs>
</x-cms::layout.admin>
