<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidaysTable extends Migration
{

    /**
     * Run the migrations.
     * @table holidays
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->increments('id_holiday');
            $table->string('name');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->text('description');
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
       Schema::dropIfExists('holidays');
     }
}
