<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('permissions')->insert([
          'name' => 'create-user',
          'display_name' => 'Create Users',
          'description' => 'Create new user',
      ]);

      DB::table('permissions')->insert([
          'name' => 'edit-user',
          'display_name' => 'Edit Users',
          'description' => 'edit existing users',
      ]);

      DB::table('permissions')->insert([
          'name' => 'delete-user',
          'display_name' => 'Delete Users',
          'description' => 'delete users',
      ]);

      DB::table('permissions')->insert([
          'name' => 'view-all-users',
          'display_name' => 'View All Users',
          'description' => 'view all users',
      ]);

      DB::table('permissions')->insert([
          'name' => 'manage-users',
          'display_name' => 'Manage Users',
          'description' => 'manage all users',
      ]);

    }
}
