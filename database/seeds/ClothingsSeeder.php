<?php

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $this->seedCategories();
        $this->seedClothingBrands();
        $this->seedClothings();
    }

    private function seedCategories() {
        $faker = Faker::create();
        $categories = array();
        for ($i = 0; $i < DatabaseConst::CLOTHING_CATEGORY_AMOUNT; $i++) {
            $categories[] = ['id' => $i + 1, 'name' => ucfirst($faker->domainWord).' '.ucfirst($faker->domainWord) ];
        }

        DatabaseSeeder::insertTimestamp('categories', $categories);
    }

    private function seedClothingBrands() {
        $faker = Faker::create();
        $brands = array();
        for ($i = 0; $i < DatabaseConst::CLOTHING_CATEGORY_AMOUNT; $i++) {
            $brands[] = ['id' => $i + 1, 'name' => $faker->company), 'country' => $faker->country ];
        }

        DatabaseSeeder::insertTimestamp('clothing_brands', $brands);
    }

    private function seedClothings() {
        $faker = Faker::create();
        $clothings = array();
        for ($i = 0; $i < DatabaseConst::CLOTHING_AMOUNT; $i++) {
            $fullname = $usernames[$i]['firstName']." ".$usernames[$i]['lastName'];
            $birth = $this->calcBirth($faker);

            $clothings[] = ['id' => $i + 1,
                'name' => substr($faker->sentence($nbWords = 4, $variableNbWords = true), 0, -1),
                'brand_id' => $faker->numberBetween(1, DatabaseConst::CLOTHING_BRAND_AMOUNT),
                'category_id' => $faker->numberBetween(1, DatabaseConst::CLOTHING_CATEGORY_AMOUNT),
                'color' => $faker->colorName,
                'size' => $faker->numberBetween(1, 7),
                'gender' => $this->calcGender($faker),
                'color' => $faker->colorName,
                'age' => $faker->numberBetween(1, 5),
                'material' => substr($faker->sentence($nbWords = 8, $variableNbWords = true), 0, -1),
                'country' => $faker->country,
                'price' => $this->calcPrice($faker),
                'discount' => $this->calcDiscount($faker),
                'description' => $faker->text($maxNbChars = 255))
            ];
        }

        DatabaseSeeder::insertTimestamp('clothings', $clothings);
    }

    private function calcGender($faker) {
        $roll = $faker->numberBetween($min = 1, $max = 10);

        if ($roll < 3) return 2;
        else if ($roll > 8) return 3;
        else return 1;
    }

    private function calcPrice($faker) {
        $a = $faker->randomNumber(20, 200, function($x) { return 1 - sqrt($x); });
        $a = $faker->randomNumber(1, 100) / 100.0;
        return $a + $b;
    }

    private function calcDiscount($faker) {
        $roll = $faker->numberBetween($min = 1, $max = 5);
        if ($roll == 1) return $roll;
        else return 5* $faker->randomNumber(1, 10, function($x) { return 1 - sqrt($x); })
    }
}
