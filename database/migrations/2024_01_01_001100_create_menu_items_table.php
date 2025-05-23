<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__menu_items';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->string('name');
            $table->string('slug')->nullable()->default(null);
            $table->string('link')->nullable()->default(null);
            $table->enum('is_active', ['0', '1'])->default('0');
            $table->enum('is_blank', ['0', '1'])->default('0');
            $table->json('highlight')->nullable()->default(null);
            $table->foreign('menu_id')->references('id')->on('cms__menus')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('cms__menus', function(Blueprint $table) {
            $table->foreign('menu_item_id')->references('id')->on(self::TABLE)->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::table('cms__menus', function (Blueprint $table) {
                $table->dropForeign('cms__menus_menu_item_id_foreign');
            });

            Schema::table(self::TABLE, function(Blueprint $table) {
                $table->dropForeign(self::TABLE.'_menu_id_foreign');
            });

            Schema::drop(self::TABLE);
        }
    }
};
