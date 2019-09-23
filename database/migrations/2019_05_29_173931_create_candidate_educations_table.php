<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_educations', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->uuid('candidate_id');
            $table->string('institution');
            $table->string('qualification');
            $table->string('field_of_study');
            $table->string('grade');
            $table->year('from_year');
            $table->year('to_year');
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
        Schema::dropIfExists('candidate_educations');
    }
}
