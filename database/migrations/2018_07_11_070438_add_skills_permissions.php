<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;
use App\Permission;

class AddSkillsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            ['name' => 'add_skills', 'display_name' => 'Add Skills', 'module_id' => 2],
            ['name' => 'view_skills', 'display_name' => 'View Skills', 'module_id' => 2],
            ['name' => 'edit_skills', 'display_name' => 'Edit Skills', 'module_id' => 2],
            ['name' => 'delete_skills', 'display_name' => 'Delete Skills', 'module_id' => 2],
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
        Permission::where('module_id', 2)->delete();
    }
}
