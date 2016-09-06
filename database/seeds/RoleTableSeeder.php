<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('roles')->insert([
          'name' => 'owner',
          'display_name' => 'Project Owner',
          'description' => 'User is the owner of a given project',
      ]);

      DB::table('roles')->insert([
          'name' => 'admin',
          'display_name' => 'User Administrator',
          'description' => 'User is allowed to manage and edit other users',
      ]);
    }
}
