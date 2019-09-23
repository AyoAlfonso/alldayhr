<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Role;

class FixUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::doesntHave('role')->get();
        $admin = Role::where('name', 'admin')->first();

        foreach ($users as $user){
            $user = User::find($user->id);

            //attach role
            $user->roles()->attach($admin->id);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
