<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const TABLES = [
        'cms__images' => ['thumb', 'filename'],
    ];

    /**
     * @return void
     */
    public function up(): void
    {
        foreach (self::TABLES as $table => $columns) {
            foreach (DB::table($table)->select(array_merge(['id'], $columns))->get() as $item) {
                $update = [];
                foreach ($columns as $column) {
                    $update[$column] = 'auto/'.basename($item->{$column});
                }

                DB::table($table)->where('id', $item->id)->update($update);
            }
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        foreach (self::TABLES as $table => $columns) {
            $columns[] = 'id';

            foreach (DB::table($table)->select($columns)->get() as $item) {
                $update = [];
                foreach ($columns as $column) {
                    $update[$column] = 'storage/app/public/auto/'.basename($item->{$column});
                }

                DB::table($table)->where('id', $item->id)->update($update);
            }
        }
    }
};
