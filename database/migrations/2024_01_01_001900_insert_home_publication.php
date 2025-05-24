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
        DB::table(self::TABLE)->where('slug', 'home')->delete();
    }
};
