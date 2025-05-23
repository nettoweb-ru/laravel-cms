<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration
{
    private const TABLE = 'cms__users__roles';
    private const COLUMNS = [
        'object_id' => 'users',
        'related_id' => 'cms__roles',
    ];
    private const DEFAULT_EMAIL = 'admin@admin.com';

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

        if ($user = DB::table('users')->where('email', self::DEFAULT_EMAIL)->get()->get(0)) {
            $roles = [];
            foreach (DB::table('cms__roles')->get() as $item) {
                $roles[$item->slug] = $item->id;
            }

            DB::table(self::TABLE)->insert([
                ['object_id' => $user->id, 'related_id' => $roles['administrator']],
                ['object_id' => $user->id, 'related_id' => $roles['developer']],
            ]);
        }
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
