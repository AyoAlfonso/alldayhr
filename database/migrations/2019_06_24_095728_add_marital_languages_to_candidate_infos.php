<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaritalLanguagesToCandidateInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_infos', function (Blueprint $table) {
            $table->string('nationality')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('languages')->nullable();
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
            //
        });
    }
}
