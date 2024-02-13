<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            $table->tinyInteger('group_id')->unsigned()->default(1);
            $table->tinyInteger('sort')->unsigned()->default(0);
            $table->string('name');
            $table->string('url');
            $table->json('highlight')->nullable()->default(null);
        });

        DB::table(self::TABLE)->insert([
            ['group_id' => 1, 'sort' => 10, 'name' => 'cms::main.list_publication', 'url' => 'admin.home', 'highlight' => '["admin.home", "admin.publication.edit", "admin.publication.create"]'],
            ['group_id' => 1, 'sort' => 20, 'name' => 'cms::main.list_album', 'url' => 'admin.album.index', 'highlight' => '["admin.album.index", "admin.album.edit", "admin.album.create", "admin.album.image.edit", "admin.album.image.create"]'],
            ['group_id' => 1, 'sort' => 30, 'name' => 'cms::main.general_browser', 'url' => 'admin.browser', 'highlight' => '["admin.browser"]'],
            ['group_id' => 20, 'sort' => 10, 'name' => 'cms::auth.profile', 'url' => 'admin.profile.edit', 'highlight' => '["admin.profile.edit"]'],
            ['group_id' => 20, 'sort' => 20, 'name' => 'cms::main.list_menu', 'url' => 'admin.menu.index', 'highlight' => '["admin.menu.index", "admin.menu.edit", "admin.menu.create", "admin.menu.menuItem.edit", "admin.menu.menuItem.create"]'],
            ['group_id' => 20, 'sort' => 30, 'name' => 'cms::main.list_language', 'url' => 'admin.language.index', 'highlight' => '["admin.language.index", "admin.language.edit", "admin.language.create"]'],
            ['group_id' => 20, 'sort' => 40, 'name' => 'cms::main.list_user', 'url' => 'admin.user.index', 'highlight' => '["admin.user.index", "admin.user.edit", "admin.user.create", "admin.user.balance.create", "admin.user.balance.edit"]'],
            ['group_id' => 20, 'sort' => 50, 'name' => 'cms::main.general_access', 'url' => 'admin.role.index', 'highlight' => '["admin.role.index", "admin.role.edit", "admin.role.create", "admin.permission.edit", "admin.permission.create"]'],
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
