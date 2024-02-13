<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const DEFAULT_EMAIL = 'admin@admin.com';
    private const TABLE = 'cms__role__user';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('cms__roles')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['user_id', 'role_id']);
        });

        $user = DB::table('users')->where('email', self::DEFAULT_EMAIL)->get()->get(0);
        $role = DB::table('cms__roles')->where('slug', CMS_ADMIN_ROLE)->get()->get(0);

        DB::table(self::TABLE)->insert([
            ['user_id' => $user->id, 'role_id' => $role->id],
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
