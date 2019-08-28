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
        $this->seedUserRoles();
        $this->seedUsers();
    }

    private function seedUserRoles() {
        $user_roles = array();
        $user_roles[] = ['id' => 1, 'name' => 'ADMIN', 'locale' => 'Admin'];
        $user_roles[] = ['id' => 2, 'name' => 'MANAGER', 'locale' => 'Manager'];
        $user_roles[] = ['id' => 3, 'name' => 'CUSTOMER', 'locale' => 'Customer'];
        $user_roles[] = ['id' => 4, 'name' => 'ETC', 'locale' => 'Etc'];

        DB::table('user_roles')->truncate();
        foreach ($user_roles as $i) {
            DB::table('user_roles')->insert($i);
        };
    }

    private function seedUsers() {
        $faker = Faker::create();

        // init list of user full name
        $totalUser = SeederConst::ADMIN_AMOUNT + SeederConst::MANAGER_AMOUNT + SeederConst::CUSTOMER_AMOUNT;
        for ($i = 0; $i < $totalUser; $i++) {
            if ($i % 2 == 0) {
                $usernames[] = ['firstName' => $faker->firstNameMale, 'lastName' => $faker->lastName, 'male' => true];
            } else {
                $usernames[] = ['firstName' => $faker->firstNameFemale, 'lastName' => $faker->lastName, 'male' => false];
            }
        }

        // insert user
        if (!isset($usernames)) return null;
        $timestamp = Carbon::now();
        $password = Hash::make(SeederConst::DEFAULT_PASSWORD);
        $users = array();

        for ($i = 0; $i < $totalUser; $i++) {
            $username = strtolower($usernames[$i]['firstName'].$usernames[$i]['lastName']);
            $user_role_id = $i < SeederConst::ADMIN_AMOUNT ? 1 : $i < SeederConst::ADMIN_AMOUNT + SeederConst::MANAGER_AMOUNT ? 2 : 3;
            $users[] = ['id' => $i + 1,
                'username' => $username,
                'email' => $username."@demo.com",
                'password' => $password,
                "user_role_id" => $user_role_id,
                "created_at" => $timestamp,
                "updated_at" => $timestamp];
        }

        DB::table('users')->truncate();
        foreach ($users as $i) {
            DB::table('users')->insert($i);
        };

        // insert manager
        $managers = array();
        for ($i = SeederConst::ADMIN_AMOUNT; $i < SeederConst::ADMIN_AMOUNT + SeederConst::MANAGER_AMOUNT; $i++) {
            $fullname = $usernames[$i]['firstName']." ".$usernames[$i]['lastName'];
            $birth = $this->calcBirth($faker);
            $managers[] = ['user_id' => $i + 1,
                'fullname' => $fullname,
                'birth' => $birth,
                'male' => $usernames[$i]['male'],
                'address' => $faker->address,
                'phone' => $faker->tollFreePhoneNumber];
        }

        DB::table('managers')->truncate();
        foreach ($managers as $i) {
            DB::table('managers')->insert($i);
        };

        // insert customer
        $customers = array();
        for ($i = SeederConst::ADMIN_AMOUNT + SeederConst::MANAGER_AMOUNT; $i < $totalUser; $i++) {
            $fullname = $usernames[$i]['firstName']." ".$usernames[$i]['lastName'];
            $birth = $this->calcBirth($faker);
            $customers[] = ['user_id' => $i + 1,
                'fullname' => $fullname,
                'birth' => $birth,
                'male' => $usernames[$i]['male'],
                'address' => $faker->address,
                'phone' => $faker->tollFreePhoneNumber];
        }

        DB::table('customers')->truncate();
        foreach ($customers as $i) {
            DB::table('customers')->insert($i);
        };
    }

    private function calcBirth($faker) {
        $year = Carbon::now()->year;
        $roll = $faker->numberBetween($min = 0, $max = 10);

        switch ($roll) {
            case 0:
                return $faker->numberBetween($year - SeederConst::YOUNG_ADULT, $year - SeederConst::ADOLESCENT);
            case 1: case 2: case 3: case 4: case 5:
                return $faker->numberBetween($year - SeederConst::MIDDLE_ADULT, $year - SeederConst::YOUNG_ADULT);
            case 6: case 7: case 8:
                return $faker->numberBetween($year - SeederConst::OLD_ADULT, $year - SeederConst::MIDDLE_ADULT);
            case 9:
                return $faker->numberBetween($year - SeederConst::UPPER_LIMIT, $year - SeederConst::OLD_ADULT);
        }

        return $year;
    }

}
