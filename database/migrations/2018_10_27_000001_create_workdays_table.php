<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkDaysTable extends Migration
{

    /**
     * Run the migrations.
     * @table work_days
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workdays', function (Blueprint $table) {
            $table->increments('id_workday');
            $table->string('name');
            $table->date('start');
            $table->date('end');
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
       Schema::dropIfExists('workdays');
     }
}
