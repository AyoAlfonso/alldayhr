<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCandidateInfoAddNyscExperience extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_infos', function (Blueprint $table) {
            $table->string("experience_level");
            $table->string("nysc_status");
            $table->integer("nysc_completion_year");
            $table->text("nysc_other_info");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_infos', function (Blueprint $table) {
            $table->dropColumn(['experience_level']);
            $table->dropColumn(['nysc_status']);
            $table->dropColumn(['nysc_completion_year']);
            $table->dropColumn(['nysc_other_info']);
        });
    }
}
