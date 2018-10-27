<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveRequestsTable extends Migration
{

    /**
     * Run the migrations.
     * @table leave_requests
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_leave_request');
            $table->unsignedInteger('id_employee');
            $table->unsignedInteger('id_leave_policy');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedInteger('days');
            $table->text('comments')->nullable();
            $table->enum('status', ['accepted', 'denied', 'pending'])->default('pending');
            $table->text('reason');
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
       Schema::dropIfExists('leave_requests');
     }
}
