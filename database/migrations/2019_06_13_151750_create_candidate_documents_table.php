<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('candidate_id');
            $table->string('document_id');
            $table->string('doc_name');
            $table->string('doc_url');
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
        Schema::dropIfExists('candidate_documents');
    }
}
