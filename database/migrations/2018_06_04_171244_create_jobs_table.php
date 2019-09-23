<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');

            $table->text('title');

            $table->mediumText('job_description');
            $table->mediumText('job_requirement');
            $table->integer('total_positions');

            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('job_locations')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('job_categories')->onUpdate('cascade')->onDelete('cascade');

            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->enum('status', ['active', 'inactive'])->default('active');

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
        Schema::dropIfExists('jobs');
    }
}
