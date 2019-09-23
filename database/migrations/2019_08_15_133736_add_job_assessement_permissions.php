<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Module;
use App\Role;
use App\Permission;
use App\PermissionRole;

class AddJobAssessementPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::insert([
            ['id' => 13, 'module_name' => 'assessment', 'description' => '']
        ]);

         $permission = ['name' => 'view_assessments', 'display_name' => 'View Assessment', 'module_id' => 13];
         $create_perm = Permission::create($permission);
 
        PermissionRole::insert([
           ['permission_id' => $create_perm->id, 'role_id' => 1]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Module::where('module_name', 'assessment')->delete();
        $role = Role::where('name', 'admin')->first();
        if($role){
            if($role->permissions->count() > 0){
                $role->permissions()->delete();
            }
            Role::where('name', 'admin')->delete();
        }
        Permission::where('name', 'view_assessments')->delete();
    }
}
