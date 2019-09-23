<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Module;
use App\Role;
use App\Permission;

class AddCompanyPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::insert([
            ['id' => 11, 'module_name' => 'company', 'description' => '']
        ]);

        $permissions = [
            ['name' => 'add_company', 'display_name' => 'Add Company', 'module_id' => 11],
            ['name' => 'view_company', 'display_name' => 'View Company', 'module_id' => 11],
            ['name' => 'edit_company', 'display_name' => 'Edit Company', 'module_id' => 11],
            ['name' => 'delete_company', 'display_name' => 'Delete Company', 'module_id' => 11],
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
        Permission::where('module_id', 11)->delete();
    }
}
