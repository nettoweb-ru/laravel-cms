@props(['balance' => 0])
<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms::tabs id="user_tab" :tabs="[1 => 'cms::main.general_properties', 2 => 'cms::main.general_balance_history']" :conditions="[2 => !empty($object->id)]">
        <x-slot name="tab1">
            <x-cms-form :url="$url" :method="$method" :objectId="$object->id">
                <x-slot name="sheet1">
                    <x-cms::form.string name="name" type="text" maxlength="255" width="9" :label="__('cms::auth.name')" :value="old('name', $object->name)" :messages="$errors->get('name')" required autofocus />
                    <x-cms::form.string name="balance" type="text" width="3" :label="__('cms::main.attr_balance')" :value="$balance" disabled />
                    <x-cms::form.string name="email" type="text" width="6" maxlength="255" :label="__('cms::auth.email')" :value="old('email', $object->email)" :messages="$errors->get('email')" required />
                    <x-cms::form.datetime name="email_verified_at" width="3" :label="__('cms::main.attr_email_verified_at')" :value="old('email_verified_at', $object->email_verified_at)" :messages="$errors->get('email_verified_at')" />
                    <x-cms::form.datetime name="created_at" width="3" :label="__('cms::main.attr_created_at')" :value="old('created_at', $object->created_at)" :disabled />
                    <x-cms::form.string name="password" type="password" width="6" :label="__('cms::auth.password_new')" :messages="$errors->get('password')" />
                    <x-cms::form.string name="password_confirmation" type="password" width="6" :label="__('cms::auth.password_confirmation')" :messages="$errors->get('password_confirmation')" />
                    @permission('manage-access')
                    <x-cms::form.checkbox name="roles" :label="__('cms::main.list_role')" :value="old('roles', $object->roles->pluck('id')->all())" :options="$reference['role']" :messages="$errors->get('roles')" multiple />
                    <x-cms::form.checkbox name="permissions" :label="__('cms::main.list_permission')" :value="old('permissions', $object->permissions->pluck('id')->all())" :options="$reference['permission']" :messages="$errors->get('permissions')" multiple />
                    @endpermission
                </x-slot>
            </x-cms-form>
        </x-slot>
        @if ($object->id)
            <x-slot name="tab2">
                <x-cms::list :url="route('admin.user.balance.list', ['user' => $object], false)" id="balance-{{ $object->id }}" />
            </x-slot>
        @endif
    </x-cms::tabs>
</x-cms::layout.admin>
