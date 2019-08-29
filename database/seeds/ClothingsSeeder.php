<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ClothingsSeeder extends Seeder
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
            $timestamp = $faker->dateTimeBetween($startDate = DatabaseConst::DEFAULT_OFFSET_YEAR.' years', $endDate = 'now')->format('Y-m-d H:i:s');
            $categories[] = ['id' => $i + 1,
                'name' => ucfirst($faker->domainWord).' '.ucfirst($faker->domainWord),
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }

        DatabaseSeeder::insert('categories', $categories);
    }

    private function seedClothingBrands() {
        $faker = Faker::create();
        $brands = array();
        for ($i = 0; $i < DatabaseConst::CLOTHING_CATEGORY_AMOUNT; $i++) {
            $timestamp = $faker->dateTimeBetween($startDate = DatabaseConst::DEFAULT_OFFSET_YEAR.' years', $endDate = 'now')->format('Y-m-d H:i:s');
            $brands[] = ['id' => $i + 1,
                'name' => $faker->company,
                'country' => $faker->country,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }

        DatabaseSeeder::insert('clothing_brands', $brands);
    }

    private function seedClothings() {
        $faker = Faker::create();
        $clothings = array();
        for ($i = 0; $i < DatabaseConst::CLOTHING_AMOUNT; $i++) {
            $timestamp = $faker->dateTimeBetween($startDate = DatabaseConst::DEFAULT_OFFSET_YEAR.' years', $endDate = 'now')->format('Y-m-d H:i:s');
            $clothings[] = ['id' => $i + 1,
                'name' => substr($faker->sentence($nbWords = 3, $variableNbWords = true), 0, -1),
                'brand_id' => $faker->numberBetween(1, DatabaseConst::CLOTHING_BRAND_AMOUNT),
                'category_id' => $faker->numberBetween(1, DatabaseConst::CLOTHING_CATEGORY_AMOUNT),
                'color' => $faker->colorName,
                'size' => $faker->numberBetween(1, 7),
                'gender' => $this->calcGender($faker),
                'age' => $faker->numberBetween(1, 5),
                'material' => substr($faker->sentence($nbWords = 3, $variableNbWords = true), 0, -1),
                'country' => $faker->country,
                'price' => $this->calcPrice($faker),
                'discount' => $this->calcDiscount($faker),
                'description' => $faker->text($maxNbChars = 128),
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }

        DatabaseSeeder::insert('clothings', $clothings);
    }

    private function calcGender($faker) {
        $roll = $faker->numberBetween($min = 1, $max = 10);

        if ($roll < 3) return 2;
        else if ($roll > 8) return 3;
        else return 1;
    }

    private function calcPrice($faker) {
        $a = $faker->numberBetween(20, 200);
        $b = $faker->numberBetween(1, 100) / 100.0;
        return $a + $b;
    }

    private function calcDiscount($faker) {
        $roll = $faker->numberBetween($min = 1, $max = 5);
        if ($roll == 1) return 0;
        else return 5* $faker->numberBetween(1, 8);
    }
}
