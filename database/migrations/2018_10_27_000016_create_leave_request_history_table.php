<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveRequestHistoryTable extends Migration
{

    /**
     * Run the migrations.
     * @table leave_request_history
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_request_history', function (Blueprint $table) {
            $table->increments('id_leave_requests_history');
            $table->unsignedInteger('id_request');
            $table->text('event')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('leave_request_history');
     }
}
