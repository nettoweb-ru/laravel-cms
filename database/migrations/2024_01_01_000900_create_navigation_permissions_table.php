<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration
{
    private const TABLE = 'cms__navigation__permissions';
    private const COLUMNS = [
        'object_id' => 'cms__navigation',
        'related_id' => 'cms__permissions',
    ];

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            foreach (self::COLUMNS as $columnName => $tableName) {
                $table->unsignedBigInteger($columnName);
                $table->foreign($columnName)->references('id')->on($tableName)->onDelete('cascade')->onUpdate('cascade');
            }

            $table->unique(array_keys(self::COLUMNS));
        });

        $permissions = [];
        foreach (DB::table('cms__permissions')->get() as $item) {
            $permissions[$item->slug] = $item->id;
        }

        $items = [];
        foreach (DB::table('cms__navigation')->get() as $item) {
            $items[$item->url] = $item->id;
        }

        DB::table(self::TABLE)->insert([
            ['object_id' => $items['admin.menu.index'], 'related_id' => $permissions['admin-menu']],
            ['object_id' => $items['admin.user.index'], 'related_id' => $permissions['admin-users']],
            ['object_id' => $items['admin.role.index'], 'related_id' => $permissions['admin-access']],
            ['object_id' => $items['admin.publication.index'], 'related_id' => $permissions['admin-publications']],
            ['object_id' => $items['admin.navigation.index'], 'related_id' => $permissions['admin-navigation']],
            ['object_id' => $items['admin.language.index'], 'related_id' => $permissions['admin-languages']],
            ['object_id' => $items['admin.browser'], 'related_id' => $permissions['admin-public-browser']],
            ['object_id' => $items['admin.album.index'], 'related_id' => $permissions['admin-photo-albums']],
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::table(self::TABLE, function(Blueprint $table) {
                foreach (array_reverse(self::COLUMNS) as $columnName => $tableName) {
                    $table->dropForeign(self::TABLE.'_'.$columnName.'_foreign');
                }
            });

            Schema::drop(self::TABLE);
        }
    }
};
