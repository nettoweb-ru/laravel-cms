<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__search';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lang_id');
            $table->string('name', 2048);
            $table->string('url', 2048);
            $table->longText('content')->nullable()->default(null);;
            $table->dateTime('updated_at')->nullable()->default(null);
            $table->foreign('lang_id')->references('id')->on('cms__lang')->onDelete('cascade')->onUpdate('cascade');
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
