<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\CompanySetting;
use App\Company;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });

        //add owners company details
        $defaultCompany = CompanySetting::first();
        $company = new Company();
        $company->company_name = $defaultCompany->company_name;
        $company->company_email = $defaultCompany->company_email;
        $company->company_phone = $defaultCompany->company_phone;
        $company->address = $defaultCompany->address;
        $company->website = $defaultCompany->website;
        $company->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
