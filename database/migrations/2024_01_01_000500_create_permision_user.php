<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const DEFAULT_EMAIL = 'admin@admin.com';
    private const TABLE = 'cms__permission__user';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('permission_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_id')->references('id')->on('cms__permissions')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['user_id', 'permission_id']);
        });

        $user = DB::table('users')->where('email', self::DEFAULT_EMAIL)->get()->get(0);

        $permissions = [];
        foreach (DB::table('cms__permissions')->whereIn('slug', ['manage-users', 'manage-access', 'manage-menu'])->get() as $item) {
            $permissions[$item->slug] = $item->id;
        }

        DB::table(self::TABLE)->insert([
            ['user_id' => $user->id, 'permission_id' => $permissions['manage-users']],
            ['user_id' => $user->id, 'permission_id' => $permissions['manage-access']],
            ['user_id' => $user->id, 'permission_id' => $permissions['manage-menu']],
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
