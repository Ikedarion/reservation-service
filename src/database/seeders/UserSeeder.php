<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;


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

        $startingIndex = 23;
        foreach (range($startingIndex, $startingIndex + 15) as $index) {
            User::create([
                'name' => $faker->name,
                'email' => 'test' . $index . '@example.com',
                'password' => bcrypt('Password1'),
                'role' => 'ユーザー',
            ]);
        }
    }
}
