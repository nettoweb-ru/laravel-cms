<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

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
            $table->longText('content')->nullable()->default(null);
            $table->string('meta_title')->nullable()->default(null);
            $table->text('meta_description')->nullable()->default(null);
            $table->text('meta_keywords')->nullable()->default(null);
            $table->string('og_title')->nullable()->default(null);
            $table->text('og_description')->nullable()->default(null);
            $table->foreign('lang_id')->references('id')->on('cms__lang')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('album_id')->references('id')->on('cms__photo_albums')->onDelete('set null')->onUpdate('cascade');
            $table->unique(['lang_id', 'slug']);
            $table->timestamps();
        });

        $language = DB::table('cms__lang')->select('id')->where('is_default', '1')->get()->get(0);

        DB::table(self::TABLE)->insert([
            'name' => 'Home',
            'lang_id' => $language->id,
            'slug' => 'home',
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::table(self::TABLE, function(Blueprint $table) {
                $table->dropForeign(self::TABLE.'_album_id_foreign');
                $table->dropForeign(self::TABLE.'_lang_id_foreign');
            });

            Schema::drop(self::TABLE);
        }
    }
};
