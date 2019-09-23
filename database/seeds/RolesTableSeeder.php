<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('name', 'admin')->first();

        // Assign admin Role
        $user = User::where('email', '=', 'admin@example.com')->first();
        $user->roles()->attach($admin->id); // id only
    }
}
