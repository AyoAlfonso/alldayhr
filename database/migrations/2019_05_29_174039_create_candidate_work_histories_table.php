<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateWorkHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_work_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->uuid('candidate_id');
            $table->string('title');
            $table->string('company');
            $table->text('job_function')->nullable();
            $table->text('achievements')->nullable();
            $table->string('industry')->nullable();
            $table->string('location')->nullable();
            $table->year('from_year');
            $table->string('from_month');
            $table->year('to_year')->nullable();
            $table->string('to_month')->nullable();
            $table->boolean('current');
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
        Schema::dropIfExists('candidate_work_histories');
    }
}
