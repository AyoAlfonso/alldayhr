<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AlterJobApplicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Add skype id field in job application table
        Schema::table('job_applications', function(Blueprint $table){
            $table->string('skype_id')->nullable()->default(null)->after('resume');
            $table->softDeletes();
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        // Remove skype id field in job application table
        Schema::table('job_applications', function(Blueprint $table){
            $table->dropColumn(['skype_id']);
        });
	}

}
