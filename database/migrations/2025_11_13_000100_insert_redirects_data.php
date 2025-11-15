<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        DB::table('cms__permissions')->insert([
            'name' => 'main.list_redirect',
            'slug' => 'admin-redirects',
            'is_system' => '1',
        ]);

        $permissions = [];
        foreach (DB::table('cms__permissions')->get() as $item) {
            $permissions[$item->slug] = $item->id;
        }

        $roles = [];
        foreach (DB::table('cms__roles')->get() as $item) {
            $roles[$item->slug] = $item->id;
        }

        DB::table('cms__roles__permissions')->insert([
            'object_id' => $roles['developer'],
            'related_id' => $permissions['admin-redirects']
        ]);

        $groups = [];
        foreach (DB::table('cms__navigation_groups')->get() as $item) {
            $groups[$item->name] = $item->id;
        }

        DB::table('cms__navigation')->insert([
            ['group_id' => $groups['main.navigation_group_settings'], 'is_system' => '1', 'sort' => 80, 'name' => 'main.list_redirect', 'url' => 'admin.redirect.index', 'highlight' => '["admin.redirect.edit", "admin.redirect.create"]'],
        ]);

        $items = [];
        foreach (DB::table('cms__navigation')->get() as $item) {
            $items[$item->url] = $item->id;
        }

        DB::table('cms__navigation__permissions')->insert([
            ['object_id' => $items['admin.redirect.index'], 'related_id' => $permissions['admin-redirects']],
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        DB::table('cms__navigation')->where('url', 'admin.redirect.index')->delete();
        DB::table('cms__permissions')->where('slug', 'admin-redirects')->delete();
    }
};
