<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration
{
    private const TABLE = 'cms__navigation_groups';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('sort')->unsigned()->default(0);
            $table->string('name');
            $table->enum('is_system', ['0', '1'])->default('0');
        });

        DB::table(self::TABLE)->insert([
            ['sort' => 10, 'is_system' => '1', 'name' => 'main.navigation_group_content'],
            ['sort' => 30, 'is_system' => '1', 'name' => 'main.navigation_group_settings'],
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
