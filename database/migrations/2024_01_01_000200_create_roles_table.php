<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration
{
    private const TABLE = 'cms__roles';
    private const CODES = [
        'administrator' => 'main.role_administrator',
        'editor' => 'main.role_editor',
        'developer' => 'main.role_developer',
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

        foreach (self::CODES as $slug => $name) {
            DB::table(self::TABLE)->insert([
                'name' => $name,
                'slug' => $slug,
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
