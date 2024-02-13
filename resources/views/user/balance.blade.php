<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms-form :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="value" type="text" maxlength="10" width="6" :label="__('cms::main.attr_value')" :value="old('value', $object->value)" :messages="$errors->get('value')" required autofocus />
            <x-cms::form.datetime name="created_at" width="3" :label="__('cms::main.attr_created_at')" :value="old('created_at', $object->created_at)" disabled />
            <x-cms::form.datetime name="updated_at" width="3" :label="__('cms::main.attr_updated_at')" :value="old('updated_at', $object->updated_at)" disabled />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
