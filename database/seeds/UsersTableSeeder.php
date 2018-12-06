<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset the users table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();

        #------ new faker object
        $faker = Factory::create();
        // generate 3 users/author
        DB::table('users')->insert([
            [
                'name' => "Alina Doe",
                'slug' => 'alina-doe',
                'email' => "alinadoe@test.com",
                'password' => bcrypt('secret'),
                'bio' => $faker->text(rand(250, 300))
            ],
            [
                'name' => "Alex Doe",
                'slug' => 'alex-doe',
                'email' => "alexdoe@test.com",
                'password' => bcrypt('secret'),
                'bio' => $faker->text(rand(250, 300))
            ],
            [
                'name' => "Manu manu",
                'slug' => 'manu-manu',
                'email' => "manumanu@test.com",
                'password' => bcrypt('secret'),
                'bio' => $faker->text(rand(250, 300))
            ],
        ]);
    }
}
