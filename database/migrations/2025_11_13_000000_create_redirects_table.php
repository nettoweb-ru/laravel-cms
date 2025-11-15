<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    private const TABLE = 'cms__redirects';

    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('source')->unique();
            $table->string('destination')->nullable()->default(null);
            $table->enum('is_active', ['0', '1'])->default('0')->index();
            $table->enum('is_regexp', ['0', '1'])->default('0');
            $table->smallInteger('status')->unsigned()->default(301);
        });
    }

    public function down(): void
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::drop(self::TABLE);
        }
    }
};
