<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__menus';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('menu_item_id')->nullable()->default(null);
            $table->unsignedBigInteger('lang_id');
            $table->foreign('lang_id')->references('id')->on('cms__lang')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['lang_id', 'slug']);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::table(self::TABLE, function(Blueprint $table) {
                $table->dropForeign(self::TABLE.'_lang_id_foreign');
            });

            Schema::drop(self::TABLE);
        }
    }
};
