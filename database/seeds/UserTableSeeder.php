<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
          'name' => 'Amberish Raj',
          'first_name' => 'Amberish',
          'last_name' => 'Raj',
          'phone' => '9876543210',
          'email' => 'amberish.raj@gmail.com',
          'password' => bcrypt('pass'),
      ]);

      DB::table('users')->insert([
          'name' => 'Jitender Inveera',
          'first_name' => 'Jitender',
          'last_name' => 'Inveera',
          'phone' => '9876543210',
          'email' => 'jitender@gmail.com',
          'password' => bcrypt('pass'),
      ]);
    }
}
