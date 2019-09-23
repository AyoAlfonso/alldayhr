<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;
use App\Permission;
use App\Module;

class AddInterviewSchedulePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = new Module();
        $module->module_name = 'schedule';
        $module->description = '';
        $module->save();

        $permissions = [
            ['name' => 'add_schedule', 'display_name' => 'Add Schedule', 'module_id' => $module->id],
            ['name' => 'view_schedule', 'display_name' => 'View Schedule', 'module_id' => $module->id],
            ['name' => 'edit_schedule', 'display_name' => 'Edit Schedule', 'module_id' => $module->id],
            ['name' => 'delete_schedule', 'display_name' => 'Delete Schedule', 'module_id' => $module->id],
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
        $module = Module::where('module_name', 'schedule')->first();
        Permission::where('module_id', $module->id)->delete();
    }
}
