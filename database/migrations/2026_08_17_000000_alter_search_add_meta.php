<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'cms__search';
    private const KEY = 'cms__search_fulltext';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->text('meta')->after('content');
            $table->fullText(['name', 'meta', 'content'], self::KEY);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->dropFullText(self::KEY);
            $table->dropColumn('meta');
        });
    }
};
