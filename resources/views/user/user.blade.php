<?php
/** @var \App\Models\User $object */
?>

<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms::tabs id="user-tabs-{{ (int) $object->id }}" :tabs="[1 => 'main.general_properties', 2 => 'main.general_balance_history']" :conditions="[2 => $object->exists]">
        <x-slot name="tab1">
            <x-cms-form id="user" :url="$url" :method="$method" :objectId="$object->id">
                <x-slot name="sheet1">
                    <x-cms::form.string name="name" width="6" maxlength="255"
                        :label="__('main.attr_name')"
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
                    <x-cms::form.string name="password" width="6" type="password"
                        :label="__('auth.password_new')"
                        :messages="$errors->get('password')"
                    />
                    <x-cms::form.string name="password_confirmation" width="6" type="password"
                        :label="__('auth.password_confirmation')"
                        :messages="$errors->get('password_confirmation')"
                    />
                    <x-cms::form.string name="balance" width="3"
                        :label="__('main.attr_balance')"
                        :value="format_number($object->getBalance(), 2)"
                        :disabled="true"
                    />
                    <x-cms::form.datetime name="email_verified_at" width="3"
                        :label="__('main.attr_email_verified_at')"
                        :value="old('email_verified_at', $object->getAttribute('email_verified_at'))"
                        :messages="$errors->get('email_verified_at')"
                    />
                    <x-cms::form.datetime name="created_at" width="3"
                        :label="__('main.attr_created_at')"
                        :value="$object->getAttribute('created_at')"
                        :disabled="true"
                    />
                    <x-cms::form.datetime name="updated_at" width="3"
                        :label="__('main.attr_updated_at')"
                        :value="$object->getAttribute('updated_at')"
                        :disabled="true"
                    />
                    @permission('admin-access')
                        <x-cms::form.checkbox name="roles"
                            :label="__('main.list_role')"
                            :value="old('roles', $object->roles->pluck('id')->all())"
                            :options="$reference['role']"
                            :multiple="true"
                        />
                        <x-cms::form.checkbox name="permissions"
                            :label="__('main.list_permission')"
                            :value="old('permissions', $object->permissions->pluck('id')->all())"
                            :options="$reference['permission']"
                            :multiple="true"
                        />
                    @endpermission
                </x-slot>
            </x-cms-form>
        </x-slot>
        @if ($object->exists)
            <x-slot name="tab2">
                <x-cms::list
                    id="user-balance-{{ $object->id }}"
                    :url="route('admin.user-balance.list', ['user' => $object])"
                    :columns="[
                        'id' => __('main.attr_id'),
                        'value' => __('main.attr_value'),
                        'created_at' => __('main.attr_created_at'),
                        'updated_at' => __('main.attr_updated_at'),
                    ]"
                    :default="['value', 'created_at']"
                    :defaultSort="['created_at' => 'desc']"
                    :actions="[
                        'create' => route('admin.user-balance.create', ['user' => $object]),
                        'delete' => route('admin.user-balance.delete', ['user' => $object]),
                    ]"
                />
            </x-slot>
        @endif
    </x-cms::tabs>
</x-cms::layout.admin>
