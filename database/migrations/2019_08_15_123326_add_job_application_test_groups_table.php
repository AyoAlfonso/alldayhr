<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobApplicationTestGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_application_test_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_application_id');
            $table->string('group_id');
            $table->string('delivery_id');
            $table->string('delivery_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_application_test_groups');
    }
}
