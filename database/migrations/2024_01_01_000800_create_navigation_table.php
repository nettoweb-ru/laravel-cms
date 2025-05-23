<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration
{
    private const TABLE = 'cms__navigation';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->tinyInteger('sort')->unsigned()->default(0);
            $table->string('name');
            $table->string('url');
            $table->enum('is_active', ['0', '1'])->default('1');
            $table->enum('is_system', ['0', '1'])->default('0');
            $table->json('highlight')->nullable()->default(null);
            $table->foreign('group_id')->references('id')->on('cms__navigation_groups')->onDelete('cascade')->onUpdate('cascade');
        });

        $groups = [];
        foreach (DB::table('cms__navigation_groups')->get() as $item) {
            $groups[$item->name] = $item->id;
        }

        DB::table(self::TABLE)->insert([
            ['group_id' => $groups['main.navigation_group_content'], 'is_system' => '1', 'sort' => 10, 'name' => 'main.list_publication', 'url' => 'admin.publication.index', 'highlight' => '["admin.publication.edit", "admin.publication.create"]'],
            ['group_id' => $groups['main.navigation_group_content'], 'is_system' => '1', 'sort' => 20, 'name' => 'main.list_album', 'url' => 'admin.album.index', 'highlight' => '["admin.album.edit", "admin.album.create", "admin.album-image.edit", "admin.album-image.create"]'],
            ['group_id' => $groups['main.navigation_group_content'], 'is_system' => '1', 'sort' => 30, 'name' => 'main.general_browser', 'url' => 'admin.browser', 'highlight' => '[]'],
            ['group_id' => $groups['main.navigation_group_settings'], 'is_system' => '1', 'sort' => 10, 'name' => 'main.list_menu', 'url' => 'admin.menu.index', 'highlight' => '["admin.menu.edit", "admin.menu.create", "admin.menu-item.edit", "admin.menu-item.create"]'],
            ['group_id' => $groups['main.navigation_group_settings'], 'is_system' => '1', 'sort' => 20, 'name' => 'main.list_language', 'url' => 'admin.language.index', 'highlight' => '["admin.language.edit", "admin.language.create"]'],
            ['group_id' => $groups['main.navigation_group_settings'], 'is_system' => '1', 'sort' => 40, 'name' => 'main.list_user', 'url' => 'admin.user.index', 'highlight' => '["admin.user.edit", "admin.user.create", "admin.user-balance.create", "admin.user-balance.edit"]'],
            ['group_id' => $groups['main.navigation_group_settings'], 'is_system' => '1', 'sort' => 50, 'name' => 'main.general_access', 'url' => 'admin.role.index', 'highlight' => '["admin.role.edit", "admin.role.create", "admin.permission.edit", "admin.permission.create"]'],
            ['group_id' => $groups['main.navigation_group_settings'], 'is_system' => '1', 'sort' => 60, 'name' => 'main.general_navigation', 'url' => 'admin.navigation.index', 'highlight' => '["admin.navigation.edit", "admin.navigation.create", "admin.navigation-item.edit", "admin.navigation-item.create"]'],
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::table(self::TABLE, function(Blueprint $table) {
                $table->dropForeign(self::TABLE.'_group_id_foreign');
            });

            Schema::drop(self::TABLE);
        }
    }
};
