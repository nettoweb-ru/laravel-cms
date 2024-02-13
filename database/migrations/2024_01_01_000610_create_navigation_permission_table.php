<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__navigation__permission';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('navigation_id');
            $table->unsignedBigInteger('permission_id');
            $table->foreign('navigation_id')->references('id')->on('cms__navigation')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_id')->references('id')->on('cms__permissions')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['navigation_id', 'permission_id']);
        });

        $permissions = [];
        foreach (DB::table('cms__permissions')->whereIn('slug', ['manage-users', 'manage-access', 'manage-menu'])->get() as $item) {
            $permissions[$item->slug] = $item->id;
        }

        $items = [];
        foreach (DB::table('cms__navigation')->whereIn('url', ['admin.menu.index', 'admin.user.index', 'admin.role.index'])->get() as $item) {
            $items[$item->url] = $item->id;
        }

        DB::table(self::TABLE)->insert([
            ['navigation_id' => $items['admin.menu.index'], 'permission_id' => $permissions['manage-menu']],
            ['navigation_id' => $items['admin.user.index'], 'permission_id' => $permissions['manage-users']],
            ['navigation_id' => $items['admin.role.index'], 'permission_id' => $permissions['manage-access']],
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
