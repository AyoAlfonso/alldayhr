<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\User();
        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = \Illuminate\Support\Facades\Hash::make('123456');
        $admin->save();
    }
}
