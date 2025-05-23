<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration
{
    private const TABLE = 'cms__permissions';
    private const CODES = [
        'admin-users' => 'main.list_user',
        'admin-access' => 'main.general_access',
        'admin-menu' => 'main.list_menu',
        'admin-navigation' => 'main.general_navigation',
        'admin-languages' => 'main.list_language',
        'admin-publications' => 'main.list_publication',
        'admin-public-browser' => 'main.general_browser',
        'admin-photo-albums' => 'main.list_album',
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
            $table->enum('is_system', ['0', '1'])->default('0');
        });

        foreach (self::CODES as $key => $value) {
            DB::table(self::TABLE)->insert([
                'name' => $value,
                'slug' => $key,
                'is_system' => '1',
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
