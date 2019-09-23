<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;
use App\Permission;
use App\Module;

class AddTeamPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::insert([
            ['id' => 8, 'module_name' => 'team', 'description' => '']
        ]);

        $permissions = [
            ['name' => 'add_team', 'display_name' => 'Add Team', 'module_id' => 8],
            ['name' => 'view_team', 'display_name' => 'View Team', 'module_id' => 8],
            ['name' => 'edit_team', 'display_name' => 'Edit Team', 'module_id' => 8],
            ['name' => 'delete_team', 'display_name' => 'Delete Team', 'module_id' => 8],
        ];

        $admin = Role::where('name', 'admin')->first();

        foreach ($permissions as $permission){

            $create = Permission::create($permission);
            $admin->attachPermission($create);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('module_id', 8)->delete();
    }
}
