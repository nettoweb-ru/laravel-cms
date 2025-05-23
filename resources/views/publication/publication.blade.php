<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="publication" :url="$url" :method="$method" :objectId="$object->id" :sheets="[1 => 'main.general_properties', 2 => 'main.general_seo']">
        <x-slot name="sheet1">
            <x-cms::form.string name="name" width="9" maxlength="255"
                :label="__('main.attr_name')"
                :value="old('name', $object->getAttribute('name'))"
                :messages="$errors->get('name')"
                :required="true"
                :autofocus="true"
                :transliterate="$object->exists ? '' : 'slug'"
            />
            <x-cms::form.select name="lang_id" width="3"
                :label="__('main.attr_language')"
                :value="old('lang_id', $object->getAttribute('lang_id'))"
                :messages="$errors->get('lang_id')"
                :options="$reference['language']"
                :required="true"
            />
            <x-cms::form.string name="slug" width="6" maxlength="255"
                :label="__('main.attr_slug')"
                :value="old('slug', $object->getAttribute('slug'))"
                :messages="$errors->get('slug')"
                :required="true"
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
            <x-cms::form.autocomplete name="album_id"
                :label="__('main.attr_album_id')"
                :value="old('album_id', $object->getAttribute('album_id'))"
                :messages="$errors->get('album_id')"
                :options="$reference['albums']"
            />
            <x-cms::form.editor name="content"
                :value="old('content', $object->getAttribute('content'))"
                :messages="$errors->get('content')"
                :language="$object->language->getAttribute('slug')"
            />
        </x-slot>
        <x-slot name="sheet2">
            <x-cms::form.string name="meta_title" maxlength="255"
                :label="__('main.attr_meta_title')"
                :value="old('meta_title', $object->getAttribute('meta_title'))"
                :messages="$errors->get('meta_title')"
            />
            <x-cms::form.text name="meta_keywords" width="6" class="h120"
                :label="__('main.attr_meta_keywords')"
                :value="old('meta_keywords', $object->getAttribute('meta_keywords'))"
                :messages="$errors->get('meta_keywords')"
            />
            <x-cms::form.text name="meta_description" width="6" class="h120"
                :label="__('main.attr_meta_description')"
                :value="old('meta_description', $object->getAttribute('meta_description'))"
                :messages="$errors->get('meta_description')"
            />
            <x-cms::form.string name="og_title" maxlength="255"
                :label="__('main.attr_og_title')"
                :value="old('og_title', $object->getAttribute('og_title'))"
                :messages="$errors->get('og_title')"
            />
            <x-cms::form.text name="og_description" class="h120"
                :label="__('main.attr_og_description')"
                :value="old('og_description', $object->getAttribute('og_description'))"
                :messages="$errors->get('og_description')"
            />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
