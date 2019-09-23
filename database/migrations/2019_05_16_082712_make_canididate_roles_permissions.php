<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Module;
use App\Role;
use App\Permission;
use App\PermissionRole;

class MakeCanididateRolesPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::insert([
            ['id' => 12, 'module_name' => 'candidate', 'description' => '']
        ]);

        $permission = ['name' => 'candidate_access', 'display_name' => 'Candidate Access', 'module_id' => 12];

        $candidate = Role::where('name', 'candidate');
        $create_perm = Permission::create($permission);
        // $candidate->attachPermission($create);
    
         $candidateRole = new Role();
         $candidateRole->name = 'candidate';
         $candidateRole->display_name = 'Candidate'; // optional
         $candidateRole->description = 'Candidate is allowed to access only candidate portal';
         $candidateRole->save();
 
        PermissionRole::insert([
           ['permission_id' => $create_perm->id, 'role_id' => $candidateRole->id]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Module::where('module_name', 'candidate')->delete();
        $role = Role::where('name', 'candidate')->first();
        if($role){
            if($role->permissions->count() > 0){
                $role->permissions()->delete();
            }
            Role::where('name', 'candidate')->delete();
        }

        Permission::where('name', 'candidate_access')->delete();
    }
}
