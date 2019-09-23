<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Module;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module_name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Module::insert([
            ['id' => 1, 'module_name' => 'job categories', 'description' => ''],
            ['id' => 2, 'module_name' => 'job skills', 'description' => ''],
            ['id' => 3, 'module_name' => 'job applications', 'description' => ''],
            ['id' => 4, 'module_name' => 'job locations', 'description' => ''],
//            ['id' => 5, 'module_name' => 'tasks', 'description' => ''],
            ['id' => 6, 'module_name' => 'jobs', 'description' => ''],
            ['id' => 7, 'module_name' => 'settings', 'description' => ''],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
