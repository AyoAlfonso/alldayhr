<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobQuestionAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_application_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_application_id')->unsigned();

            $table->foreign('job_application_id')->references('id')->on('job_applications')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('job_id')->unsigned();

            $table->foreign('job_id')->references('id')->on('jobs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('question_id')->unsigned();

            $table->foreign('question_id')->references('id')->on('questions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->mediumText('answer')->nullable()->default(null);

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
        Schema::dropIfExists('job_application_answers');
    }
}
