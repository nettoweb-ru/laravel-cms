<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__menu_item__role';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('menu_item_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('menu_item_id')->references('id')->on('cms__menu_items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('cms__roles')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['menu_item_id', 'role_id']);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
