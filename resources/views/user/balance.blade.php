<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="user-balance" :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="value" width="6" maxlength="18"
                :label="__('main.attr_value')"
                :value="old('value', $object->getAttribute('value'))"
                :messages="$errors->get('value')"
                :required="true"
                :autofocus="true"
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
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
