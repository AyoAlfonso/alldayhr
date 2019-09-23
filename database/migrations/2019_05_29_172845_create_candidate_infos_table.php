<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('candidate_id');
            $table->integer('user_id');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender',['M', 'F'])->nullable();
            $table->string('phone_number', 36)->nullable();
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->string('street')->nullable();
            $table->string('landmark')->nullable();
            $table->json('certifications')->nullable();
            $table->json('skills')->nullable();
            $table->string('cv_url')->nullable();
            $table->string('cv_name')->nullable();
            $table->string('profile_image_url')->nullable();
            $table->enum('status', ['enabled', 'disabled']);
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
        Schema::dropIfExists('candidate_infos');
    }
}
