<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLanguageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('language_code');
            $table->string('language_name');
            $table->enum('status', ['enabled', 'disabled']);
            $table->timestamps();
        });

        $languages = [
            ['language_code' => 'ar', 'language_name' => 'Arabic', 'status' => 'disabled'],
            ['language_code' => 'de', 'language_name' => 'German', 'status' => 'disabled'],
            ['language_code' => 'es', 'language_name' => 'Spanish', 'status' => 'enabled'],
            ['language_code' => 'et', 'language_name' => 'Estonian', 'status' => 'disabled'],
            ['language_code' => 'fa', 'language_name' => 'Farsi', 'status' => 'disabled'],
            ['language_code' => 'fr', 'language_name' => 'French', 'status' => 'enabled'],
            ['language_code' => 'gr', 'language_name' => 'Greek', 'status' => 'disabled'],
            ['language_code' => 'it', 'language_name' => 'Italian', 'status' => 'disabled'],
            ['language_code' => 'nl', 'language_name' => 'Dutch', 'status' => 'disabled'],
            ['language_code' => 'pl', 'language_name' => 'Polish', 'status' => 'disabled'],
            ['language_code' => 'pt', 'language_name' => 'Portuguese', 'status' => 'disabled'],
            ['language_code' => 'pt-br', 'language_name' => 'Portuguese (Brazil)', 'status' => 'disabled'],
            ['language_code' => 'ro', 'language_name' => 'Romanian', 'status' => 'disabled'],
            ['language_code' => 'ru', 'language_name' => 'Russian', 'status' => 'enabled'],
            ['language_code' => 'tr', 'language_name' => 'Turkish', 'status' => 'disabled'],
            ['language_code' => 'zh-CN', 'language_name' => 'Chinese (S)', 'status' => 'disabled'],
            ['language_code' => 'zh-TW', 'language_name' => 'Chinese (T)', 'status' => 'disabled'],
        ];

        DB::table('language_settings')->insert($languages);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_settings');
    }
}
