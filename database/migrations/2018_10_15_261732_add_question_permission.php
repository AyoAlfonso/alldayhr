<?php


use Illuminate\Database\Migrations\Migration;
use App\Role;
use App\Permission;
use App\Module;

class AddQuestionPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::insert([
            ['id' => 9, 'module_name' => 'question', 'description' => '']
        ]);

        $permissions = [
            ['name' => 'add_question', 'display_name' => 'Add Question', 'module_id' => 9],
            ['name' => 'view_question', 'display_name' => 'View Question', 'module_id' => 9],
            ['name' => 'edit_question', 'display_name' => 'Edit Question', 'module_id' => 9],
            ['name' => 'delete_question', 'display_name' => 'Delete Question', 'module_id' => 9],
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
        Permission::where('module_id', 9)->delete();
    }
}
