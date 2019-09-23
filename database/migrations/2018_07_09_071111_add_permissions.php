<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;
use App\Role;

class AddPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('module_id')->unsigned()->after('description');
            $table->foreign('module_id')->references('id')->on('modules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        $permissions = [
            ['name' => 'add_category', 'display_name' => 'Add Category', 'module_id' => 1],
            ['name' => 'view_category', 'display_name' => 'View Category', 'module_id' => 1],
            ['name' => 'edit_category', 'display_name' => 'Edit Category', 'module_id' => 1],
            ['name' => 'delete_category', 'display_name' => 'Delete Category', 'module_id' => 1],
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'module_id' => 7],
        ];

        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'App Administrator'; // optional
        $admin->description = 'Admin is allowed to manage everything of the app.'; // optional
        $admin->save();

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
        Permission::where('module_id', 1)->delete();
        Permission::where('module_id', 7)->delete();

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn(['module_id']);
        });
    }
}
