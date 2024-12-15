<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;


use function Ramsey\Uuid\v1;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');

        $startingIndex = 3;

        User::create([
            'name' => $faker->name,
            'email' => 'user@example.com',
            'password' => bcrypt('Password1'),
            'role' => 'ユーザー',
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'name' => $faker->name,
            'email' => 'admin@example.com',
            'password' => bcrypt('Password1'),
            'role' => '管理者',
            'email_verified_at' => Carbon::now(),
        ]);

        foreach (range($startingIndex, $startingIndex + 19) as $index) {
            User::create([
                'name' => $faker->name,
                'email' => 'test' . $index . '@example.com',
                'password' => bcrypt('Password1'),
                'role' => '店舗代表者',
                'email_verified_at' => Carbon::now(),
            ]);
        }
    }
}
