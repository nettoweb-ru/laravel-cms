<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__permissions';
    private const CODES = [
        'manage-users' => 'Управление пользователями',
        'manage-access' => 'Управление доступом',
        'manage-menu' => 'Управление меню',
    ];

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
        });

        foreach (self::CODES as $key => $value) {
            DB::table(self::TABLE)->insert([
                'name' => $value,
                'slug' => $key,
            ]);
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
