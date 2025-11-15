<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="redirect" :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="source" width="6" maxlength="255"
                :label="__('main.attr_source')"
                :value="old('source', $object->getAttribute('source'))"
                :messages="$errors->get('source')"
                :required="true"
                :autofocus="true"
            />
            <x-cms::form.string name="destination" width="6" maxlength="255"
                :label="__('main.attr_destination')"
                :value="old('destination', $object->getAttribute('destination'))"
                :messages="$errors->get('destination')"
            />
            <x-cms::form.select name="status" width="6"
                :label="__('main.attr_status')"
                :value="old('status', $object->getAttribute('status'))"
                :messages="$errors->get('status')"
                :options="$reference['status']"
                :required="true"
            />
            <x-cms::form.checkbox name="is_active" width="3"
                :label="__('main.attr_is_active')"
                :value="old('is_active', $object->getAttribute('is_active'))"
                :messages="$errors->get('is_active')"
                :options="$reference['boolean']"
            />
            <x-cms::form.checkbox name="is_regexp" width="3"
                :label="__('main.attr_is_regexp')"
                :value="old('is_regexp', $object->getAttribute('is_regexp'))"
                :messages="$errors->get('is_regexp')"
                :options="$reference['boolean']"
            />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
