<?php

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
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

        DatabaseSeeder::insert('user_roles', $user_roles);
    }

    private function seedUsers() {
        $faker = Faker::create();

        // init list of user full name
        $totalUser = DatabaseConst::ADMIN_AMOUNT + DatabaseConst::MANAGER_AMOUNT + DatabaseConst::CUSTOMER_AMOUNT;
        for ($i = 0; $i < $totalUser; $i++) {
            $timestamp = $faker->dateTimeBetween($startDate = DatabaseConst::DEFAULT_OFFSET_YEAR.' years', $endDate = 'now')->format('Y-m-d H:i:s');
            $usernames[] = $i % 2 == 0 ?
                ['firstName' => $faker->firstNameMale, 'lastName' => $faker->lastName, 'male' => true, 'timestamp' => $timestamp] :
                ['firstName' => $faker->firstNameFemale, 'lastName' => $faker->lastName, 'male' => false, 'timestamp' => $timestamp];
        }

        // insert user
        if (!isset($usernames)) return null;
        $password = Hash::make(DatabaseConst::DEFAULT_PASSWORD);
        $users = array();
        for ($i = 0; $i < $totalUser; $i++) {
            $username = strtolower($usernames[$i]['firstName'].$usernames[$i]['lastName']);
            $users[] = ['id' => $i + 1,
                'username' => $username,
                'email' => $username.'@'.$faker->freeEmailDomain,
                'password' => $password,
                'user_role_id' => $i < DatabaseConst::ADMIN_AMOUNT ? 1 : ($i < DatabaseConst::ADMIN_AMOUNT + DatabaseConst::MANAGER_AMOUNT ? 2 : 3),
                'created_at' => $usernames[$i]['timestamp'],
                'updated_at' => $usernames[$i]['timestamp']
            ];
        }

        DatabaseSeeder::insert('users', $users);

        // insert manager
        $managers = array();
        for ($i = DatabaseConst::ADMIN_AMOUNT; $i < DatabaseConst::ADMIN_AMOUNT + DatabaseConst::MANAGER_AMOUNT; $i++) {
            $managers[] = ['user_id' => $i + 1,
                'fullname' => $usernames[$i]['firstName'].' '.$usernames[$i]['lastName'],
                'birth' => $this->calcBirth($faker),
                'male' => $usernames[$i]['male'],
                'address' => $faker->streetAddress.', '.$faker->city.', '.$faker->country,
                'phone' => strtok($faker->e164PhoneNumber,' '),
                'created_at' => $usernames[$i]['timestamp'],
                'updated_at' => $usernames[$i]['timestamp']
            ];
        }

        DatabaseSeeder::insert('managers', $managers);

        // insert customer
        $customers = array();
        for ($i = DatabaseConst::ADMIN_AMOUNT + DatabaseConst::MANAGER_AMOUNT; $i < $totalUser; $i++) {
            $customers[] = ['user_id' => $i + 1,
                'fullname' => $usernames[$i]['firstName'].' '.$usernames[$i]['lastName'],
                'birth' => $this->calcBirth($faker),
                'male' => $usernames[$i]['male'],
                'address' => $faker->streetAddress.', '.$faker->city.', '.$faker->country,
                'phone' => strtok($faker->e164PhoneNumber,' '),
                'created_at' => $usernames[$i]['timestamp'],
                'updated_at' => $usernames[$i]['timestamp']
            ];
        }

        DatabaseSeeder::insert('customers', $customers);
    }

    private function calcBirth($faker) {
        $year = Carbon::now()->year;
        $roll = $faker->numberBetween($min = 1, $max = 10);

        if ($roll <= 1) return $faker->numberBetween($year - DatabaseConst::YOUNG_ADULT, $year - DatabaseConst::ADOLESCENT);
        else if ($roll <= 5) return $faker->numberBetween($year - DatabaseConst::YOUNG_ADULT, $year - DatabaseConst::ADOLESCENT);
        else if ($roll <= 9) return $faker->numberBetween($year - DatabaseConst::OLD_ADULT, $year - DatabaseConst::MIDDLE_ADULT);
        else return $faker->numberBetween($year - DatabaseConst::UPPER_LIMIT, $year - DatabaseConst::OLD_ADULT);
    }

}
