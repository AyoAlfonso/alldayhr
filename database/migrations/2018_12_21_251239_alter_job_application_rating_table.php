<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AlterJobApplicationRatingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Add skype id field in job application table
        Schema::table('job_applications', function(Blueprint $table){
            $table->enum('rating', [1,2,3,4,5])->nullable()->default(null)->after('resume');
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
            $table->dropColumn(['rating']);
        });
	}

}
