<?php

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $this->seedComments();
    }

    private function seedComments() {
        $faker = Faker::create();
        $comments = array();
        $customerList = $this->calcCustomerList($faker);
        $clothingList = $this->calcClothingList($faker);
        $customerNumber = count($customerList);
        $clothingNumber = count($clothingList);

        for ($i = 0; $i < DatabaseConst::CUSTOMER_COMMENT_AMOUNT; $i++) {
            $timestamp = $faker->dateTimeBetween($startDate = DatabaseConst::DEFAULT_OFFSET_YEAR.' years', $endDate = 'now')->format('Y-m-d H:i:s');
            $comments[] = ['id' => $i + 1,
                'customer_id' => $customerList[$faker->numberBetween(1, $customerNumber) - 1],
                'clothing_id' => $clothingList[$faker->numberBetween(1, $clothingNumber) - 1],
                'rating' => $faker->numberBetween(1, 5),
                'comment' => $faker->text($maxNbChars = 255),
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }

        DatabaseSeeder::insert('customer_comments', $comments);
    }

    private function calcCustomerList($faker) {
        $min = DatabaseConst::ADMIN_AMOUNT + DatabaseConst::MANAGER_AMOUNT;
        $max = DatabaseConst::ADMIN_AMOUNT + DatabaseConst::MANAGER_AMOUNT + DatabaseConst::CUSTOMER_AMOUNT;
        $amount = DatabaseConst::CUSTOMER_AMOUNT / 5;

        $a = array();
        for ($i = 0; $i < $amount; $i++) {
            $a[] = $faker->numberBetween($min, $max);
        }
        return $a;
    }

    private function calcClothingList($faker) {
        $amount = DatabaseConst::CLOTHING_AMOUNT / 5;

        $a = array();
        for ($i = 0; $i < $amount; $i++) {
            $a[] = $faker->numberBetween(1, DatabaseConst::CLOTHING_AMOUNT);
        }
        return $a;
    }
}
