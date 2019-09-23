<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaoSubClassColumnToTaoGroupToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tao_group_to_jobs', function (Blueprint $table) {
              $table->text('tao_sub_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tao_group_to_jobs', function (Blueprint $table) {
            //
        });
    }
}
