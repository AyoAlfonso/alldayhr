<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;
use App\Permission;

class AddJobsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            ['name' => 'add_jobs', 'display_name' => 'Add Jobs', 'module_id' => 6],
            ['name' => 'view_jobs', 'display_name' => 'View Jobs', 'module_id' => 6],
            ['name' => 'edit_jobs', 'display_name' => 'Edit Jobs', 'module_id' => 6],
            ['name' => 'delete_jobs', 'display_name' => 'Delete Jobs', 'module_id' => 6],
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
        Permission::where('module_id', 6)->delete();
    }
}
