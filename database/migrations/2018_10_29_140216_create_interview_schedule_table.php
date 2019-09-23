<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_application_id')->unsigned();

            $table->foreign('job_application_id')->references('id')->on('job_applications')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->dateTime('schedule_date');
            $table->enum('status',['approve','refuse','pending'])->default('pending');
            $table->enum('user_accept_status',['accept','refuse','waiting'])->default('waiting');
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
        Schema::dropIfExists('interview_schedules');
    }
}
