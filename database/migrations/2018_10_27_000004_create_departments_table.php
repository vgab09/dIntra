<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     * @table departments
     *
     * @return void
     */
    public function up()
    {

        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id_department');
            $table->string('name');
            $table->unsignedInteger('id_leader')->nullable();
            $table->unsignedInteger('id_parent')->nullable();
            $table->tinyInteger('active')->default('1');
            $table->text('description')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('departments');
     }
}
