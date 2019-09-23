<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;
use App\Role;

class AddJobApplicationPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            ['name' => 'add_job_applications', 'display_name' => 'Add Job Applications', 'module_id' => 3],
            ['name' => 'view_job_applications', 'display_name' => 'View Job Applications', 'module_id' => 3],
            ['name' => 'edit_job_applications', 'display_name' => 'Edit Job Applications', 'module_id' => 3],
            ['name' => 'delete_job_applications', 'display_name' => 'Delete Job Applications', 'module_id' => 3],
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
        Permission::where('module_id', 3)->delete();
    }
}
