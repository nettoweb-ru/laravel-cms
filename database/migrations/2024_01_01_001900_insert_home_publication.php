<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const TABLE = 'cms__publications';

    /**
     * @return void
     */
    public function up(): void
    {
        if (!DB::table(self::TABLE)->where('slug', 'home')->count()) {
            $language = DB::table('cms__lang')->select('id')->where('is_default', '1')->first();

            DB::table(self::TABLE)->insert([
                'name' => 'Home',
                'lang_id' => $language->id,
                'slug' => 'home',
            ]);
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        DB::table(self::TABLE)->where('slug', 'home')->delete();
    }
};
