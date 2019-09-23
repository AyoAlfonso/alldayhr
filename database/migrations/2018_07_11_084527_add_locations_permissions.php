<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;
use App\Permission;

class AddLocationsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            ['name' => 'add_locations', 'display_name' => 'Add Location', 'module_id' => 4],
            ['name' => 'view_locations', 'display_name' => 'View Location', 'module_id' => 4],
            ['name' => 'edit_locations', 'display_name' => 'Edit Location', 'module_id' => 4],
            ['name' => 'delete_locations', 'display_name' => 'Delete Location', 'module_id' => 4],
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
        Permission::where('module_id', 4)->delete();
    }
}
