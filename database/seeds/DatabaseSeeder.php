<?php

use Carbon\Carbon;
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
        DB::table($table_name)->insert($data);
    }
    
    public static function insertTimestamp($table_name, $data)
    {
        for ($data as $d) {
            $timestamp = dateTimeBetween($startDate = DatabaseConst::DEFAULT_OFFSET_YEAR.' years', $endDate = 'now');
            $d['created_at'] = $timestamp;
            $d['updated_at'] = $timestamp;
        }
    
        DB::table($table_name)->truncate();
        DB::table($table_name)->insert($data);
    }
}
