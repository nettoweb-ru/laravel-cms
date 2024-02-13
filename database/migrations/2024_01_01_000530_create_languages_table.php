<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__languages';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort')->default(0);
            $table->string('name');
            $table->char('slug', 2)->unique();
            $table->char('locale', 5);
            $table->enum('is_default', ['0', '1'])->default('0');
        });

        DB::table(self::TABLE)->insert([
            ['sort' => 10, 'name' => 'Русский', 'slug' => 'ru', 'locale' => 'ru_RU', 'is_default' => '1'],
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
