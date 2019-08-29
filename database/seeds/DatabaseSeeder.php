<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(ClothingsSeeder::class);
        $this->call(CommentsSeeder::class);
    }

    public static function insert($table_name, $data)
    {
        DB::table($table_name)->truncate();
        $amount = count($data);
        if ($amount <= DatabaseConst::TRANSACTION_LIMIT) DB::table($table_name)->insert($data);
        else self::insertMultiple($table_name, $data, $amount / DatabaseConst::TRANSACTION_LIMIT);
    }

    public static function insertMultiple($table_name, $data, $n) {
        for ($i = 0; $i < $n; $i++) {
            $a = array_slice($data, $i*DatabaseConst::TRANSACTION_LIMIT, DatabaseConst::TRANSACTION_LIMIT);
            DB::table($table_name)->insert($a);
        }

        $a = array_slice($data, $n*DatabaseConst::TRANSACTION_LIMIT);
        DB::table($table_name)->insert($a);
    }
}
