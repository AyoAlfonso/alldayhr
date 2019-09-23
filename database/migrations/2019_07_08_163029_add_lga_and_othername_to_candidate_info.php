<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLgaAndOthernameToCandidateInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_infos', function (Blueprint $table) {
            $table->string('othername')->nullable();
            $table->string('residence_state')->nullable();
            $table->string('residence_lga')->nullable();
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
            $table->dropColumn(['residence_lga','residence_state','othername']);
        });
    }
}
