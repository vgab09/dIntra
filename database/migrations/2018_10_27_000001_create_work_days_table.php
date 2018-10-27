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
        Schema::create('work_days', function (Blueprint $table) {
            $table->increments('id_work_day');
            $table->string('title');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->text('description')->nullable();
            $table->timestamp('crated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('work_days');
     }
}
