<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms::tabs id="profile-tabs" :tabs="[1 => 'auth.profile', 2 => 'auth.update_password']">
        <x-slot name="tab1">
            <x-cms-form id="profile" :url="['save' => route('admin.profile.update')]" method="patch" :objectId="$object->id" :apply="false">
                <x-slot name="sheet1">
                    <x-cms::form.string name="name" width="6" maxlength="255"
                        :label="__('auth.name')"
                        :value="old('name', $object->getAttribute('name'))"
                        :messages="$errors->get('name')"
                        :required="true"
                        :autofocus="true"
                    />
                    <x-cms::form.string name="email" width="6" maxlength="255"
                        :label="__('auth.email')"
                        :value="old('email', $object->getAttribute('email'))"
                        :messages="$errors->get('email')"
                        :required="true"
                    />
                </x-slot>
            </x-cms-form>
        </x-slot>
        <x-slot name="tab2">
            <x-cms-form id="password" :url="['save' => route('admin.profile.password.update')]" method="put" :objectId="$object->id" :apply="false">
                <x-slot name="sheet1">
                    <x-cms::form.string name="current_password" type="password" autocomplete="current-password"
                        :label="__('auth.password_current')"
                        :messages="$errors->updatePassword->get('current_password')"
                        :required="true"
                        :autofocus="true"
                    />
                    <x-cms::form.string name="password" type="password" width="6" autocomplete="new-password"
                        :label="__('auth.password_new')"
                        :messages="$errors->updatePassword->get('password')"
                        :required="true"
                    />
                    <x-cms::form.string name="password_confirmation" type="password" width="6" autocomplete="new-password"
                        :label="__('auth.password_confirmation')"
                        :messages="$errors->updatePassword->get('password_confirmation')"
                    />
                </x-slot>
            </x-cms-form>
        </x-slot>
    </x-cms::tabs>
</x-cms::layout.admin>
