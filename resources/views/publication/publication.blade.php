<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms-form id="publication" :url="$url" :method="$method" :objectId="$object->id" :sheets="[1 => 'cms::main.general_properties', 2 => 'cms::main.general_seo']">
        <x-slot name="sheet1">
            <x-cms::form.string name="name" type="text" width="9" maxlength="255" :label="__('cms::main.attr_name')" :value="old('name', $object->name)" :messages="$errors->get('name')" transliterate="{{ $object->exists ? '' : 'slug' }}" required autofocus />
            <x-cms::form.select name="lang_id" width="3" :options="$reference['language']" :label="__('cms::main.attr_language')" :value="old('lang_id', $object->lang_id)" :messages="$errors->get('lang_id')" required />
            <x-cms::form.string name="slug" type="text" width="6" maxlength="255" :label="__('cms::main.attr_slug')" :value="old('slug', $object->slug)" :messages="$errors->get('slug')" required />
            <x-cms::form.datetime name="created_at" width="3" :label="__('cms::main.attr_created_at')" :value="old('created_at', $object->created_at)" disabled />
            <x-cms::form.datetime name="updated_at" width="3" :label="__('cms::main.attr_updated_at')" :value="old('updated_at', $object->updated_at)" disabled />
            <x-cms::form.select name="album_id" :options="$reference['albums']" :label="__('cms::main.attr_album_id')" :value="old('album_id', $object->album_id)" :messages="$errors->get('album_id')" />
            <x-cms::form.editor name="content" :value="old('content', $object->content)" :lang="$object->language->slug" :messages="$errors->get('content')" />
        </x-slot>
        <x-slot name="sheet2">
            <x-cms::form.string name="meta_title" type="text" maxlength="255" :label="__('cms::main.attr_meta_title')" :value="old('meta_title', $object->meta_title)" :messages="$errors->get('meta_title')" />
            <x-cms::form.text name="meta_keywords" width="6" class="h120"  :label="__('cms::main.attr_meta_keywords')" :value="old('meta_keywords', $object->meta_keywords)" :messages="$errors->get('meta_keywords')" />
            <x-cms::form.text name="meta_description" width="6" class="h120"  :label="__('cms::main.attr_meta_description')" :value="old('meta_description', $object->meta_description)" :messages="$errors->get('meta_description')" />
            <x-cms::form.string name="og_title" type="text" maxlength="255" :label="__('cms::main.attr_og_title')" :value="old('og_title', $object->og_title)" :messages="$errors->get('og_title')" />
            <x-cms::form.text name="og_description" class="h120"  :label="__('cms::main.attr_og_description')" :value="old('og_description', $object->og_description)" :messages="$errors->get('og_description')" />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
