<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaoGroupToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tao_group_to_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_id');
            $table->string('tao_group_uri');
            $table->string('tao_group_name');
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
        Schema::dropIfExists('tao_group_to_jobs');
    }
}
