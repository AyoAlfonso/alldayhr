<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateApplicationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->timestamps();
        });

        $data = [
            ['status' => 'applied'],
            ['status' => 'phone screen'],
            ['status' => 'interview'],
            ['status' => 'hired'],
            ['status' => 'rejected']
        ];

        DB::table('application_status')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_status');
    }
}
