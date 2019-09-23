<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Module;
use App\Role;
use App\Permission;
use App\PermissionRole;

class AddViewCandidatePoolPermissionToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        $permission = ['name' => 'view_candidate_pool', 'display_name' => 'View Candidate Pool', 'module_id' => 12];
        $candidate = Role::where('name', 'admin');
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
    public function down() {
        Permission::where('module_id', 12)->delete();


    }
}
