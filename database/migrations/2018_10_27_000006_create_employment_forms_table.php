<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmploymentFormsTable extends Migration
{

    /**
     * Run the migrations.
     * @table employment_forms
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_forms', function (Blueprint $table) {
            $table->increments('id_employment_form');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('employment_forms');
     }
}
