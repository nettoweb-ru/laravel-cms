<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const TABLE = 'users';
    private const DEFAULT_EMAIL = 'admin@admin.com';

    /**
     * @return void
     */
    public function up(): void
    {
        if (DB::table(self::TABLE)->where('email', self::DEFAULT_EMAIL)->get()->count() == 0) {
            DB::table(self::TABLE)->insert([
                ['name' => 'Administrator', 'email' => self::DEFAULT_EMAIL, 'password' => '$2y$12$HK7LTQQQhqDyazG.0UrR4.xiXOGQeQ3gEv9jd4Kl35Qz3JG18OdLm', 'email_verified_at' => date('Y-m-d H:i:s')],
            ]);
        }

    }

    /**
     * @return void
     */
    public function down(): void
    {
        DB::table(self::TABLE)->where('email', self::DEFAULT_EMAIL)->delete();
    }
};
