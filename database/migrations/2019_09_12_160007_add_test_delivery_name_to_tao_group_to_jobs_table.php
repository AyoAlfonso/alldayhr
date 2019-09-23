<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestDeliveryNameToTaoGroupToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tao_group_to_jobs', function (Blueprint $table) {
            $table->string('delivery_name')->nullable();
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
           $table->dropColumn('delivery_name');
           $table->dropColumn('delivery_uri');
        });
    }
}
