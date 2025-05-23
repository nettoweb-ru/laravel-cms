<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__images';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->unsignedBigInteger('album_id');
            $table->foreign('album_id')->references('id')->on('cms__photo_albums')->onDelete('cascade')->onUpdate('cascade');
            $table->string('thumb');
            $table->string('filename');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::table(self::TABLE, function(Blueprint $table) {
                $table->dropForeign(self::TABLE.'_album_id_foreign');
            });

            Schema::drop(self::TABLE);
        }
    }
};
