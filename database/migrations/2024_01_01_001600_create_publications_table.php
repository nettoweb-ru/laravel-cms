<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__publications';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('lang_id');
            $table->unsignedBigInteger('album_id')->nullable()->default(null);
            $table->string('slug');
            $table->longText('content');
            $table->string('meta_title')->nullable()->default(null);
            $table->text('meta_description');
            $table->text('meta_keywords');
            $table->string('og_title')->nullable()->default(null);
            $table->text('og_description');
            $table->foreign('lang_id')->references('id')->on('cms__languages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('album_id')->references('id')->on('cms__albums')->onDelete('set null')->onUpdate('cascade');
            $table->unique(['lang_id', 'slug']);
            $table->timestamps();
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
